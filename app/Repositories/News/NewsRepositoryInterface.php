<?php

namespace App\Repositories\News;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface NewsRepositoryInterface
 * @package App\Repositories\User
 */
interface NewsRepositoryInterface
{
    public function getAll(): Collection;

    public function getSearchResultAtPager(array $params, int $limit): LengthAwarePaginator;
}
