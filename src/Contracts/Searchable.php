<?php


namespace LaravelVueGoodTable\Contracts;


use Illuminate\Database\Query\Builder;

interface Searchable
{
    /**
     * @return bool
     */
    public function isSearchable(): bool;

    /**
     * @param Builder $queryBuilder
     * @param string  $searchQuery
     *
     * @return Builder
     */
    public function search($queryBuilder, string $searchQuery);
}