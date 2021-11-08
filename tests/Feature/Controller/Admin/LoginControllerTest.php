<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * @test
     */
    public function Loginページは正常に表示される()
    {
        $res = $this->get(route('login'));
        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function Login処理は正常である()
    {
        $user = User::factory()->create();

        $params = [
            'email'    => 'admin@example.com',
            'password' => 'admin1234'
        ];

        $res = $this->actingAs($user, 'admin')->post('/admin/login', $params);

        $res->assertRedirect(route('admin.dashboard'));
    }

    /**
     * @test
     */
    public function Logout処理は正常である()
    {
        $res = $this->get('/admin/logout');

        $res->assertRedirect(route('login'));
    }

}
