<?php


namespace LaravelVueGoodTable\Contracts;


use Illuminate\Database\Query\Builder;

interface Filterable
{
    /**
     * @return bool
     */
    public function isFilterable(): bool;

    /**
     * @param Builder $queryBuilder
     * @param array   $values
     *
     * @return Builder
     */
    public function filter($queryBuilder, array $values);
}