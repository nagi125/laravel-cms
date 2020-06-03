<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * トップページ
     * @return View
     */
    public function index(): View
    {
        $title = 'トップページ';
        $description = 'テストディスクリプション';

        $data = [
            'title' => $title,
            'description' => $description,
        ];

        return view('admin.dashboard', $data);
    }
    //
}
