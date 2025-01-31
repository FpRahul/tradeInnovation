<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Role extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_stages')->insert([
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Client',
            ],
            [
                'name' => 'Associate',
            ],
            [
                'name' => 'Employee',
            ],
            [
                'name' => 'Project Manager',
            ]
        ]);
    }
}
