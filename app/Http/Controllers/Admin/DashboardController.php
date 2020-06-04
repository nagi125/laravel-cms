<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * トップページ
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * ダッシュボード
     * @return View
     */
    public function dashboard(): View
    {
        $title = 'トップページ';
        $description = 'テストディスクリプション';

        $data = [
            'title' => $title,
            'description' => $description,
        ];

        return view('admin.dashboard', $data);
    }
}
