<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '山田 太郎',
                'kana' => 'ヤマダ タロウ',
                'email' => 'admin@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt(env('ADMIN_USER_PASS')),
                'role' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::select("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");
    }
}
