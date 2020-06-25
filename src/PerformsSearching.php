<?php


namespace LaravelVueGoodTable;


use Illuminate\Database\Query\Builder;
use LaravelVueGoodTable\Contracts\Searchable;
use LaravelVueGoodTable\Http\Requests\DataRequest;

trait PerformsSearching
{
    /**
     * @param array       $columns
     * @param Builder     $queryBuilder
     * @param DataRequest $request
     *
     * @return mixed
     */
    protected function applySearching(array $columns, $queryBuilder, DataRequest $request)
    {
        $sq = $request->getSearchQuery();
        if (empty($sq)) {
            return $queryBuilder;
        }

        $queryBuilder->where(function ($query) use ($request, $columns, $sq) {
            foreach ($columns as $column) {
                if (($column instanceof Searchable) and $column->isSearchable()) {
                    $column->search($query, $sq);
                }
            }
        });

        return $queryBuilder;
    }
}