<?php

namespace Database\Seeders;

use App\Enums\ResumeTheme;
use App\Models\GeneralOption;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
        ]);

        GeneralOption::where('user_id', $user->id)->first()->update([
            'slug' => 'admin',
            'theme' => ResumeTheme::DEFAULT->value,
        ]);
    }
}
