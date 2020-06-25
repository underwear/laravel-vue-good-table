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

        $sortField = $sort['field'];
        $sortType = $sort['type'];

        foreach ($columns as $column) {
            if ($column->getAttribute() == $sortField and ($column instanceof Sortable) and $column->isSortable()) {
                $queryBuilder = $column->sort($queryBuilder, $sortType);
            }
        }

        return $queryBuilder;
    }
}