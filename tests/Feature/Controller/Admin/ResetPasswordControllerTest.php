<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    /**
     * @test
     */
    public function パスワードリセット画面は正常に表示される()
    {
        $res = $this->get(route('password.reset', ['token' => 'hogefugapiyo']));
        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function パスワードリセット処理は正常である()
    {
        /** @var Authenticatable $user */
        $user = User::query()->find(1);

        $params = [
            'email'    => 'admin@example.com',
            'password' => 'Admin1234',
        ];

        $res = $this->actingAs($user, 'admin')->post(route('password.update'), $params);
        $res->assertRedirect(route('admin.dashboard'));
    }

}
