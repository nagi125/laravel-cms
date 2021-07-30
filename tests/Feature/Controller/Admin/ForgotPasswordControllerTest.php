<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    /**
     * @test
     */
    public function パスワード再発行用メールアドレス入力画面は正常に表示される()
    {
        $res = $this->get(route('password.request'));
        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function パスワード再発行用メール送信処理は正常である()
    {
        $params = [
            'email' => 'admin@example.com'
        ];

        Mail::fake();
        $res = $this->from(route('password.request'))
            ->post(route('password.email'), $params);

        $res->assertRedirect(route('password.request'));
    }

}
