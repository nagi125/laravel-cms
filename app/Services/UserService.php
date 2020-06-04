<?php
namespace App\Services;

use App\Repositories\User\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    protected UserRepository $userRepository;
    protected UtilityService $utility;

    public function __construct(UserRepository $userRepository, UtilityService $utility)
    {
        $this->userRepository = $userRepository;
        $this->utility = $utility;
    }

    /**
     * 管理画面の検索画面の初期化
     * @param  Request  $request
     * @return array
     */
    public function initIndexParamsForAdmin(Request $request): array
    {
        $params = [];
        if (empty($request->query())) {
            $params = [
                'keyword' => ''
            ];
        } else {
            $params = $request->query();
        }

        return $params;
    }

    /**
     * @param  array  $params
     * @param  int  $limit
     * @return LengthAwarePaginator
     */
    public function getSearchResultAtPagerForAdmin(array $params, int $limit): LengthAwarePaginator
    {
        return $this->userRepository->getSearchResultAtPager($params, $limit);
    }

}