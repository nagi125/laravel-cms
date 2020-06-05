<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use App\Services\UtilityService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class TopController
 * @package App\Http\Controllers
 */
class TopController extends Controller
{
    protected UtilityService $utility;
    protected NewsService $newsService;

    /**
     * TopController constructor.
     * @param  UtilityService  $utility
     * @param  NewsService  $newsService
     */
    public function __construct(UtilityService $utility, NewsService $newsService)
    {
        $this->utility = $utility;
        $this->newsService = $newsService;
    }

    /**
     * トップページ
     * @return View
     */
    public function index(): View
    {
        $title = 'トップページ';
        $description = 'テストディスクリプション';

        $news = $this->newsService->getAllForFront(5);

        $data = [
            'news'  => $news,
            'title' => $title,
            'description' => $description,
        ];

        return view('top', $data);
    }
}
