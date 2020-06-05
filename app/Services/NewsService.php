<?php
namespace App\Services;

use App\Repositories\News\NewsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class NewsService
 * @package App\Services
 */
class NewsService
{
    protected NewsRepository $newsRepository;
    protected UtilityService $utility;

    public function __construct(NewsRepository $newsRepository, UtilityService $utility)
    {
        $this->newsRepository = $newsRepository;
        $this->utility = $utility;
    }

    /**
     * @param  int  $limit
     * @return Collection
     */
    public function getAllForFront(int $limit): Collection
    {
        return $this->newsRepository->getAllForFront($limit);
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
        return $this->newsRepository->getSearchResultAtPager($params, $limit);
    }

}