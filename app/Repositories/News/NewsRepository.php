<?php
namespace App\Repositories\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return News::all();
    }

    /**
     * @param $params
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getSearchResultAtPager(array $params, int $limit): LengthAwarePaginator
    {
        $query = News::query();

        if ($params['keyword']) {
            $query->where('title', 'like', "%$params[keyword]%");
        }

        $news = $query->orderBy('public_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return $news;
    }

}