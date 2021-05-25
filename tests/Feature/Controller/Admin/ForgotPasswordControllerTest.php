<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
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
     * Todo: Actions環境下のみ500エラー吐くので要調査
     */
    public function パスワード再発行用メール送信処理は正常である()
    {
        $params = [
            'email' => 'admin@example.com'
        ];

        $res = $this->post(route('password.email'), $params);
        $res->assertStatus(302);
    }

}
