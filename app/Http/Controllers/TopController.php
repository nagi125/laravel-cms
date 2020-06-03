<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class TopController
 * @package App\Http\Controllers
 */
class TopController extends Controller
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

        return view('top', $data);
    }
}
