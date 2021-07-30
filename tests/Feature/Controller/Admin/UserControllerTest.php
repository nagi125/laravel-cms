<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @test
     */
    public function ユーザー管理のindexの表示は正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.users.index'));

        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function ユーザー管理の新規作成ページは正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.users.create'));

        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function ユーザー管理の編集ページは正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.users.edit', ['user' => 1]));

        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function ユーザー管理の登録処理は正常である()
    {
        $user = User::factory()->create();

        $randStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 8);

        $userData = [
            'name'  => '山田 花子',
            'kana'  => 'ヤマダ ハナコ',
            'email' => $randStr. '@example.com',
            'password' => 'admin1234',
            'password_confirmation' => 'admin1234',
            'role' => 1,
        ];

        $res = $this->actingAs($user, 'admin')
            ->post(route('admin.users.store'), $userData);

        $res->assertRedirect(route('admin.users.index'));
    }

    /**
     * @test
     */
    public function ユーザー管理のアップデート処理は正常である()
    {
        $user = User::factory()->create();

        $updateUser = User::query()->inRandomOrder()->first();
        $randStr = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 8);

        $userData = [
            'name'  => '山田 花子',
            'kana'  => 'ヤマダ ハナコ',
            'email' => $randStr. '@example.com',
            'password' => 'admin1234',
            'password_confirmation' => 'admin1234',
            'role' => 1,
        ];

        $res = $this->actingAs($user, 'admin')
            ->put(route('admin.users.update', ['user' => $updateUser->id]), $userData);

        $res->assertRedirect(route('admin.users.index'));
    }

    /**
     * @test
     */
    public function ユーザー管理の削除処理は正常である()
    {
        $user = User::factory()->create();

        $deleteUser = User::query()->inRandomOrder()->first();

        $res = $this->actingAs($user, 'admin')
            ->delete(route('admin.users.destroy', ['user' => $deleteUser->id]));

        $res->assertRedirect(route('admin.users.index'));
    }
}
