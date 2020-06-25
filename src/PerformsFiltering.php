<?php


namespace LaravelVueGoodTable;


use Illuminate\Database\Query\Builder;
use LaravelVueGoodTable\Columns\Column;
use LaravelVueGoodTable\Contracts\Filterable;
use LaravelVueGoodTable\Http\Requests\DataRequest;

trait PerformsFiltering
{
    /**
     * @return Column[]
     */
    abstract protected function getColumns(): array;

    /**
     * @param Builder     $queryBuilder
     * @param DataRequest $request
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function applyFiltering($queryBuilder, DataRequest $request)
    {
        $columnFilters = $request->getColumnFilters();

        foreach ($this->getColumns() as $column) {

            if ($this->canBeFiltered($column) and array_key_exists($column->getAttribute(), $columnFilters)) {

                $filterValues = $columnFilters[$column->getAttribute()];

                if (!is_array($filterValues)) {
                    $filterValues = [$filterValues];
                }

                /** @var Filterable|Column $column */
                $queryBuilder = $column->filter($queryBuilder, $filterValues);
            }

        }

        return $queryBuilder;
    }

    /**
     * @param Column $column
     *
     * @return bool
     */
    protected function canBeFiltered(Column $column): bool
    {
        return ($column instanceof Filterable) and $column->isFilterable();
    }

}