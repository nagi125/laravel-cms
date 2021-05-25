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
        $params = [
            'email'    => 'admin@example.com',
            'password' => 'admin1234'
        ];

        $res = $this->post('/admin/login', $params);

        $res->assertRedirect(route('admin.dashboard'));
    }

    /**
     * @test
     */
    public function Logout処理は正常である()
    {
        $res = $this->post('/admin/logout');

        $res->assertRedirect(route('login'));
    }

}
