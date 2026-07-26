<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    private const EMAIL = 'admin@example.com';

    private const NAME = 'Admin';

    private const PASSWORD = 'password';

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => self::EMAIL],
            [
                'name' => self::NAME,
                'password' => self::PASSWORD,
            ],
        );
    }
}
