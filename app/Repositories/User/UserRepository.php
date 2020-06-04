<?php
namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @param $params
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getSearchResultAtPager(array $params, int $limit): LengthAwarePaginator
    {
        $query = User::query();

        if ($params['keyword']) {
            $query->where('name', 'like', "%$params[keyword]%");
        }

        $users = $query->orderBy('id', 'desc')
            ->paginate($limit);

        return $users;
    }

}