<?php


namespace LaravelVueGoodTable\Contracts;


use Illuminate\Database\Query\Builder;

interface Sortable
{
    /**
     * @return bool
     */
    public function isSortable(): bool;

    /**
     * @param Builder $queryBuilder
     * @param string  $type
     *
     * @return Builder
     */
    public function sort($queryBuilder, string $type = 'asc');
}