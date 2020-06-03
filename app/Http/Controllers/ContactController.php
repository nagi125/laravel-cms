<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Class ContactController
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{
    /**
     * お問い合わせ
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
}
