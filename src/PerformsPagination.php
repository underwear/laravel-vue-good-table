<?php


namespace LaravelVueGoodTable;


use Illuminate\Database\Query\Builder;
use LaravelVueGoodTable\Http\Requests\DataRequest;

trait PerformsPagination
{
    /**
     * @param Builder     $queryBuilder
     * @param DataRequest $request
     *
     * @return mixed
     */
    protected function applyPagination($queryBuilder, DataRequest $request)
    {
        $page = $request->getPage();
        $perPage = $request->getPerPage();
        $offset = $perPage * ($page - 1);

        return $queryBuilder
            ->limit($perPage)
            ->offset($offset);
    }
}