<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class ContactController
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{
    /**
     * お問い合わせ
     * @Method GET
     * @return View
     */
    public function index(): View
    {
        $title = 'お問い合わせ';
        $description = 'テストディスクリプション';

        $data = [
            'title' => $title,
            'description' => $description,
        ];

        return view('contact', $data);
    }

    /**
     * サンキューページ
     * @Method GET
     * @return View
     */
    public function thanks(): View
    {
        $title = 'サンキューページ';
        $description = '';

        $data = [
            'title' => $title,
            'description' => $description,
        ];

        return view('thanks', $data);
    }
}
