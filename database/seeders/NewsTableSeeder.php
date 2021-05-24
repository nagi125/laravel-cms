<?php
namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news')->insert([
            [
                'title' => 'ニュースタイトル1',
                'public_date' => '2020-05-11 00:00:00',
                'body' => 'ニュース機能のテスト投稿です。その1',
                'image' => '',
                'description' => 'ニュースタイトル1のDescriptionです。',
                'is_public' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'ニュースタイトル2',
                'public_date' => '2020-06-01 00:00:00',
                'body' => 'ニュース機能のテスト投稿です。その2',
                'image' => '',
                'description' => 'ニュースタイトル2のDescriptionです。',
                'is_public' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::select("SELECT setval('news_id_seq', (SELECT MAX(id) FROM news))");
    }
}
