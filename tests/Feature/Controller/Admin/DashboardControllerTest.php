<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /**
     * @test
     */
    public function indexはdashboardページにリダイレクトされる()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.top'));

        $res->assertRedirect(route('admin.dashboard'));
    }

    /**
     * @test
     */
    public function ダッシュボードの表示は正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.dashboard'));

        $res->assertStatus(200);
    }
}
