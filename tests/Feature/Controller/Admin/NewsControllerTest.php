<?php

namespace Tests\Feature\Controller\Admin;

use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function お知らせ管理のindexの表示は正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.news.index'));

        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function お知らせ管理の新規作成ページは正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.news.create'));

        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function お知らせ管理の編集ページは正常である()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user, 'admin')
            ->get(route('admin.news.edit', ['news' => 1]));

        $res->assertStatus(200);
    }

    /**
     * @test
     */
    public function お知らせ管理の登録処理は正常である()
    {
        $user = User::factory()->create();

        $postData = [
            'public_date' => Carbon::now()->format('Y-m-d'),
            'title' => 'テスト',
            'body'  => 'テスト',
        ];

        $res = $this->actingAs($user, 'admin')
            ->post(route('admin.news.store'), $postData);

        $res->assertRedirect(route('admin.news.index'));
    }

    /**
     * @test
     */
    public function お知らせ管理のアップデート処理は正常である()
    {
        $user = User::factory()->create();

        $updatePost = News::query()->inRandomOrder()->first();

        $putData = [
            'public_date' => Carbon::now()->format('Y-m-d'),
            'title' => 'テスト',
            'body'  => 'テスト',
        ];

        $res = $this->actingAs($user, 'admin')
            ->put(route('admin.news.update', ['news' => $updatePost->id]), $putData);

        $res->assertRedirect(route('admin.news.index'));
    }

    /**
     * @test
     */
    public function お知らせ管理の削除処理は正常である()
    {
        $user = User::factory()->create();

        $deletePost = News::query()->inRandomOrder()->first();

        Storage::fake();
        $res = $this->actingAs($user, 'admin')
            ->delete(route('admin.news.destroy', ['news' => $deletePost->id]));

        $res->assertRedirect(route('admin.news.index'));
    }
}
