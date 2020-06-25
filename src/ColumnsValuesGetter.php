<?php


namespace LaravelVueGoodTable;


use LaravelVueGoodTable\Columns\Column;

class ColumnsValuesGetter
{
    /**
     * @param Column[]   $columns
     * @param Iterable $rows
     *
     * @return array
     */
    public function handle(array $columns, Iterable $rows): array
    {
        $items = [];
        foreach ($rows as $row) {
            $values = [];
            foreach ($columns as $column) {
                $values[$column->getAttribute()] = $column->getDisplayedValued($row);
            }
            $items[] = $values;
        }

        return $items;
    }
}