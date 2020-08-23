<?php


namespace LaravelVueGoodTable\Columns;


use Illuminate\Database\Query\Builder;
use LaravelVueGoodTable\Columns\Options\FilterOptions;
use LaravelVueGoodTable\Contracts\Filterable;
use LaravelVueGoodTable\Contracts\Searchable;
use LaravelVueGoodTable\Contracts\Sortable;

class Text extends Column implements Searchable, Sortable, Filterable
{
    /**
     * @var bool
     */
    protected $searchable = false;

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var FilterOptions
     */
    protected $filterOptions;

    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * @var bool
     */
    protected $html = false;

    /**
     * @var string|null
     */
    protected $width = null;

    /**
     * @var null|string
     */
    protected $whereClauseAttribute;

    /**
     * @param string|null $value
     *
     * @return static
     */
    public function whereClauseAttribute(?string $value = null)
    {
        $this->whereClauseAttribute = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function getWhereClauseAttribute(): string
    {
        return $this->whereClauseAttribute ?? $this->attribute;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @param bool $searchable
     *
     * @return static
     */
    public function searchable(?bool $searchable = true)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     *
     * @return static
     */
    public function sortable(?bool $sortable = true)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterable(): bool
    {
        return $this->filterOptions ? $this->filterOptions->enabled : false;
    }

    /**
     * @param bool|null   $isFilterable
     * @param string|null $placeholder
     * @param array|null  $filterDropdownItems
     * @param bool|null   $multiSelect
     * @param string|null $trigger
     *
     * @return Text
     */
    public function filterable(
        ?bool $isFilterable = true,
        ?string $placeholder = 'Filter this',
        ?array $filterDropdownItems = [],
        ?bool $multiSelect = false,
        ?string $trigger = ''
    ): self {
        $this->filterOptions = new FilterOptions(
            $isFilterable,
            $placeholder,
            $filterDropdownItems,
            $multiSelect,
            $trigger
        );

        return $this;
    }

    /**
     * @param Builder $queryBuilder
     * @param array   $values
     *
     * @return Builder
     */
    public function filter($queryBuilder, array $values)
    {
        return $queryBuilder->where(function ($query) use ($values) {
            foreach ($values as $value) {
                $attributeName = $this->getWhereClauseAttribute();
                $query->orWhere($attributeName, $value);
            }
        });
    }

    /**
     * @param bool|null $value
     *
     * @return Text
     */
    public function html(?bool $value = true): self
    {
        $this->html = $value;

        return $this;
    }

    /**
     * @param string|null $value
     *
     * @return Text
     */
    public function width(?string $value): self
    {
        $this->width = $value;

        return $this;
    }

    /**
     * @param Builder $queryBuilder
     * @param string  $searchQuery
     *
     * @return Builder
     */
    public function search($queryBuilder, string $searchQuery)
    {
        $comparison = $this->connectionType == 'mysql' ? 'like' : 'ilike';

        return $queryBuilder->orWhere($this->getWhereClauseAttribute(), $comparison, "%{$searchQuery}%");
    }

    /**
     * @param Builder $queryBuilder
     * @param string  $type
     *
     * @return Builder
     */
    public function sort($queryBuilder, string $type = 'asc')
    {
        return $queryBuilder->orderBy($this->getAttribute(), $type);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'sortable' => $this->isSortable(),
            'html' => $this->html,
            'filterOptions' => $this->filterOptions ? $this->filterOptions->jsonSerialize() : null,
            'width' => $this->width
        ]);
    }
}