<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Services extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'serviceName' => 'Trademark',
                'status' => '1'
            ],
            [
                'serviceName' => 'Patnet',
                'status' => '1'
            ],
            [
                'serviceName' => 'Copyright',
                'status' => '1'
            ],
            [
                'serviceName' => 'Design',
                'status' => '1'
            ]
        ]); 
    }
}
