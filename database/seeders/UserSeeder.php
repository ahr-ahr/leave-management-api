<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Employee One',
            'email' => 'employee1@gmail.com',
            'password' => 'password',
            'role' => UserRole::EMPLOYEE,
        ]);

        User::query()->create([
            'name' => 'Employee Two',
            'email' => 'employee2@gmail.com',
            'password' => 'password',
            'role' => UserRole::EMPLOYEE,
        ]);
    }
}
