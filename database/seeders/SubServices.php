<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubServices extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_services')->insert([
            [
                'serviceId' => '1',
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '1',
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '1',
                'subServiceName' => 'Modification',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '1',
                'subServiceName' => 'Annual returns',
                'subServiceDescription' => 'Annual returns'
            ],
            [
                'serviceId' => '1',
                'subServiceName' => 'Legal notice',
                'subServiceDescription' => 'Legal notice'
            ],
            [
                'serviceId' => '1',
                'subServiceName' => 'Litigation',
                'subServiceDescription' => 'Litigation'
            ],
            [
                'serviceId' => '2',
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '2',
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '2',
                'subServiceName' => 'Modification',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '2',
                'subServiceName' => 'Annual returns',
                'subServiceDescription' => 'Annual returns'
            ],
            [
                'serviceId' => '2',
                'subServiceName' => 'Legal notice',
                'subServiceDescription' => 'Legal notice'
            ],
            [
                'serviceId' => '2',
                'subServiceName' => 'Litigation',
                'subServiceDescription' => 'Litigation'
            ],
            [
                'serviceId' => '3',
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '3',
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '3',
                'subServiceName' => 'Modification',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '3',
                'subServiceName' => 'Annual returns',
                'subServiceDescription' => 'Annual returns'
            ],
            [
                'serviceId' => '3',
                'subServiceName' => 'Legal notice',
                'subServiceDescription' => 'Legal notice'
            ],
            [
                'serviceId' => '3',
                'subServiceName' => 'Litigation',
                'subServiceDescription' => 'Litigation'
            ],
            [
                'serviceId' => '4',
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '4',
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '4',
                'subServiceName' => 'Modification',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '4',
                'subServiceName' => 'Annual returns',
                'subServiceDescription' => 'Annual returns'
            ],
            [
                'serviceId' => '4',
                'subServiceName' => 'Legal notice',
                'subServiceDescription' => 'Legal notice'
            ],
            [
                'serviceId' => '4',
                'subServiceName' => 'Litigation',
                'subServiceDescription' => 'Litigation'
            ]
        ]); 
    }
}
