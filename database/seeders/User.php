<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'role' => '1',
                'email' => 'admin@gmail.com',
                'mobile' => '0123456789',
                'companyName' => 'Tradeinnovation',
                'password' => '$2y$12$xM3llIi5q0fGi6CX2bzsU.1nC3gmRwUdJCkL59D6SlTzfFReZNC8S',
            ]
        ]);
    }
}
