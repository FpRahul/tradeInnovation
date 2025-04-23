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
                'client_type' => 1,
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '1',
                'client_type' => 1,
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '1',
                'client_type' => 1,
                'subServiceName' => 'Assignment/registered user',
                'subServiceDescription' => 'Assignment/registered user'
            ],
            [
                'serviceId' => '1',
                'client_type' => 1,
                'subServiceName' => 'Objection',
                'subServiceDescription' => 'Objection'
            ],
            [
                'serviceId' => '1',
                'client_type' => 2,
                'subServiceName' => 'Opposition',
                'subServiceDescription' => 'Opposition'
            ],
            [
                'serviceId' => '1',
                'client_type' => 1,
                'subServiceName' => 'Applicant Adress Change',
                'subServiceDescription' => 'Applicant Adress Change'
            ],
            [
                'serviceId' => '1',
                'client_type' => 2,
                'subServiceName' => 'Rectification',
                'subServiceDescription' => 'Rectification'
            ],
            [
                'serviceId' => '1',
                'client_type' => 1,
                'subServiceName' => 'Request for Expedite process',
                'subServiceDescription' => 'Request for Expedite process'
            ],
            
            [
                'serviceId' => '1',
                'client_type' => 1,
                'subServiceName' => 'IP Watch',
                'subServiceDescription' => 'IP Watch'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '2',
                'client_type' => 2,
                'subServiceName' => 'opposition',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'Assignment/License',
                'subServiceDescription' => 'Assignment/License'
            ],
            [
                'serviceId' => '2',
                'client_type' => 2,
                'subServiceName' => 'Post-grant opposition',
                'subServiceDescription' => 'Post-grant opposition'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'Assignment/Registered User',
                'subServiceDescription' => 'Assignment/Registered User'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'Applicant Address Change',
                'subServiceDescription' => 'Applicant Adress Change'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'PCT Filing',
                'subServiceDescription' => 'PCT Filing'
            ],
            [
                'serviceId' => '2',
                'client_type' => 1,
                'subServiceName' => 'Hearing',
                'subServiceDescription' => 'Hearing'
            ],
            [
                'serviceId' => '3',
                'client_type' => 1,
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '3',
                'client_type' => 1,
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '3',
                'client_type' => 1,
                'subServiceName' => 'Modification',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '3',
                'client_type' => 1,
                'subServiceName' => 'Annual returns',
                'subServiceDescription' => 'Annual returns'
            ],
            [
                'serviceId' => '3',
                'client_type' => 1,
                'subServiceName' => 'Legal notice',
                'subServiceDescription' => 'Legal notice'
            ],
            [
                'serviceId' => '3',
                'client_type' => 1,
                'subServiceName' => 'Litigation',
                'subServiceDescription' => 'Litigation'
            ],
            [
                'serviceId' => '4',
                'client_type' => 1,
                'subServiceName' => 'New Registration',
                'subServiceDescription' => 'New Registration'
            ],
            [
                'serviceId' => '4',
                'client_type' => 1,
                'subServiceName' => 'Renewal',
                'subServiceDescription' => 'Renewal'
            ],
            [
                'serviceId' => '4',
                'client_type' => 1,
                'subServiceName' => 'Modification',
                'subServiceDescription' => 'Modification'
            ],
            [
                'serviceId' => '4',
                'client_type' => 1,
                'subServiceName' => 'Annual returns',
                'subServiceDescription' => 'Annual returns'
            ],
            [
                'serviceId' => '4',
                'client_type' => 1,
                'subServiceName' => 'Legal notice',
                'subServiceDescription' => 'Legal notice'
            ],
            [
                'serviceId' => '4',
                'client_type' => 1,
                'subServiceName' => 'Litigation',
                'subServiceDescription' => 'Litigation'
            ]
        ]); 
    }
}
