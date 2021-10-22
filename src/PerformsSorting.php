<?php


namespace LaravelVueGoodTable;


use Illuminate\Database\Query\Builder;
use LaravelVueGoodTable\Columns\Column;
use LaravelVueGoodTable\Contracts\Sortable;
use LaravelVueGoodTable\Http\Requests\DataRequest;

trait PerformsSorting
{
    /**
     * @param Column[]    $columns
     * @param Builder     $queryBuilder
     * @param DataRequest $request
     *
     * @return mixed
     */
    protected function applySorting(array $columns, $queryBuilder, DataRequest $request)
    {
        $sort = $request->getSort();
        if (empty($sort)) {
            return $queryBuilder;
        }

        $column_map = [];
        foreach ($columns as $index => $column) {
            $column_map[$column->getAttribute()] = $index;
        }

        foreach ($sort as $item) {
            if (!isset($item['field']) || !isset($item['type'])) {
                continue;
            }
            if (!in_array(strtolower($item['type']), ['asc', 'desc'])) {
                continue;
            }

            if (isset($column_map[$item['field']]) && isset($columns[$column_map[$item['field']]])) {
                $column = $columns[$column_map[$item['field']]];
                $queryBuilder = $column->sort($queryBuilder, $item['type']);
            }
        }

        return $queryBuilder;
    }
}
