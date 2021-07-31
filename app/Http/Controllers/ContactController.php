<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactPostRequest;
use App\Mail\ContactMail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

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
     * @param  ContactPostRequest  $request
     * @return RedirectResponse
     */
    public function submit(ContactPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Mail::send(new ContactMail($validated));

        return response()->redirectTo(route('contact.thanks'));
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
