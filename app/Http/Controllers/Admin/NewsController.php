<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Inquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsPostRequest;
use App\Models\News;
use App\Services\NewsService;
use App\Services\UtilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;

/**
 * Class NewsController
 * @package App\Http\Controllers\Admin
 */
class NewsController extends Controller
{
    const SELECT_LIMIT = 15;

    private string $uploadTo = 'uploads/news';
    protected NewsService $newsService;
    protected UtilityService $utility;

    /**
     * NewsController constructor.
     * @param  NewsService  $newsService
     * @param  UtilityService  $utility
     */
    public function __construct(NewsService $newsService, UtilityService $utility)
    {
        $this->newsService = $newsService;
        $this->utility = $utility;
    }

    /**
     * 一覧
     * @Method GET
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $params = $this->newsService->initIndexParamsForAdmin($request);

        $news = $this->newsService->getSearchResultAtPagerForAdmin($params, self::SELECT_LIMIT);

        $title = 'お知らせ一覧';

        $data = [
            'news'   => $news,
            'params' => $params,
            'title'  => $title,
        ];

        return view('admin.news.index', $data);
    }

    /**
     * 新規作成画面
     * @Method GET
     * @param  News  $news
     * @return View
     */
    public function create(News $news): View
    {
        $title = 'お知らせ新規作成';

        $data = [
            'news'  => $news,
            'title' => $title,
        ];

        return view('admin.news.create', $data);
    }

    /**
     * 編集画面
     * @Method GET
     * @param  News  $news
     * @return View
     */
    public function edit(News $news): View
    {
        $imageUrl = $news->image ? Storage::disk('s3')->url($news->image) . '?' .date('YmdHis') : '';

        $title = 'お知らせの編集: '. $news->title;

        $data = [
            'news'     => $news,
            'imageUrl' => $imageUrl,
            'title'    => $title,
        ];

        return view('admin.news.edit', $data);
    }

    /**
     * 新規保存処理
     * @Method POST
     * @param  NewsPostRequest  $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(NewsPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $id   = $this->utility->getNextId('news');
            $path = $this->utility->uploadFileToS3($id, $this->uploadTo, $request);

            $validated['image'] = $path;

            $news = new News;
            $news->fill($validated)->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::critical($e->getMessage());

            session()->flash('critical_error_message', '保存中に問題が発生しました。');
            return redirect()->back()->withInput();
        }

        session()->flash('flash_message', '新規作成が完了しました');

        return redirect()->route('admin.news.index');
    }

    /**
     * アップデート
     * @Method PUT
     * @param  NewsPostRequest $request
     * @param  News  $news
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(NewsPostRequest $request, News $news): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            if ($request->is_delete) {
                $this->utility->deleteS3File($news->image);
                $path = '';
            } else {
                $path = $news->image ?: $this->utility->uploadFileToS3($news->id, $this->uploadTo, $request);
            }

            $validated['image'] = $path;
            $news->fill($validated)->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::critical($e->getMessage());

            session()->flash('critical_error_message', '保存中に問題が発生しました。');
            return redirect()->back()->withInput();
        }

        session()->flash('flash_message', '更新が完了しました');

        return redirect()->route('admin.news.index');
    }

    /**
     * 削除
     * @Method DELETE
     * @param  News  $news
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $news): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->utility->deleteS3File($news->image);
            $news->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            Log::critical($e->getMessage());
            session()->flash('critical_error_message', '削除中に問題が発生しました。');

            return redirect()->back()->withInput();
        }

        session()->flash('flash_message', $news->title.'を削除しました');

        return redirect()->route('admin.news.index');
    }
}
