<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserPostRequest;
use App\Models\User;
use App\Services\UserService;
use App\Services\UtilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;


/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    const SELECT_LIMIT = 15;

    protected UserService $userService;
    protected UtilityService $utility;

    /**
     * UserController constructor.
     * @param  UserService  $userService
     * @param  UtilityService  $utility
     */
    public function __construct(UserService $userService, UtilityService $utility)
    {
        $this->userService = $userService;
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
        $params = $this->userService->initIndexParamsForAdmin($request);

        $users = $this->userService->getSearchResultAtPagerForAdmin($params, self::SELECT_LIMIT);

        $title = 'ユーザー一覧';

        $data = [
            'users' => $users,
            'params' => $params,
            'title'  => $title,
        ];

        return view('admin.users.index', $data);
    }

    /**
     * 新規作成画面
     * @Method GET
     * @param  User  $user
     * @return View
     */
    public function create(User $user): View
    {
        $title = '新規ユーザー作成';

        $data = [
            'user'  => $user,
            'title' => $title,
        ];

        return view('admin.users.create', $data);
    }

    /**
     * 編集画面
     * @Method GET
     * @param  User  $user
     * @return View
     */
    public function edit(User $user): View
    {
        $title = 'ユーザーの編集: '. $user->name;

        $data = [
            'user'  => $user,
            'title' => $title,
        ];

        return view('admin.users.edit', $data);
    }

    /**
     * 新規保存処理
     * @Method POST
     * @param  UserPostRequest  $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(UserPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        $user = new User;
        $user->fill($validated)->save();

        session()->flash('flash_message', '新規作成が完了しました');

        return redirect()->route('admin.users.index');
    }

    /**
     * アップデート
     * @Method PUT
     * @param  UserPostRequest $request
     * @param  User  $user
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(UserPostRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        $user->fill($validated)->save();

        session()->flash('flash_message', '更新が完了しました');

        return redirect()->route('admin.users.index');
    }

    /**
     * 削除
     * @Method DELETE
     * @param  User  $user
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        session()->flash('flash_message', $user->title.'を削除しました');

        return redirect()->route('admin.users.index');
    }
}
