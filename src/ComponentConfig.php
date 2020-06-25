<?php


namespace LaravelVueGoodTable;


use LaravelVueGoodTable\Columns\Column;
use LaravelVueGoodTable\Options\PaginationOptions;
use LaravelVueGoodTable\Options\SearchOptions;

class ComponentConfig
{
    /**
     * @var Column[]
     */
    protected $columns;

    /**
     * ComponentConfig constructor.
     *
     * @param Column[]          $columns
     */
    public function __construct(
        array $columns
    ) {
        $this->columns = $columns;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}