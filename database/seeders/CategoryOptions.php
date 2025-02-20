<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryOptions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_options')->insert([
            [
                'authId' => '1',
                'type' => '1',
                'name' => 'CA',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '1',
                'name' => 'CS',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '1',
                'name' => 'Advocate',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '1',
                'name' => 'Accountant',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '1',
                'name' => 'Business Consultant',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'Individual / Sole Proprietor',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'Partnership',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'LLP',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'Company',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'Society',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'Trust',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'HUF',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '2',
                'name' => 'Educational institute',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Google',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Facebook',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Newspaper',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Client referral',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Associate',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Employee',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '3',
                'name' => 'Website',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '4',
                'name' => 'Textiles',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '4',
                'name' => 'Garments',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '4',
                'name' => 'IT Consultant',
                'status' => '1',
            ],
            [
                'authId' => '1',
                'type' => '4',
                'name' => 'Automobile',
                'status' => '1',
            ],
        ]);
    }
}
