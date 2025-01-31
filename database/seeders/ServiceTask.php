<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTask extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_stages')->insert([
            [
                'service_id' => '1',
                'title' => 'Search trademark',
                'description' => 'Search trademark on online portal to ensure the uniqueness',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Documentation & Drafting',
                'description' => 'Search trademark on online portal to ensure the uniqueness',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Approval & Send quotation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Opposition',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Counter statment (Opposition)',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Evidence Submission (Opposition)',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Show cause hearing (Opposition)',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Formality check',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Filing',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Examination',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Objection',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Counter statment (Objection)',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Evidence submission (Objection)',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Show cause hearing (Objection)',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Registration',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Renewal',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Rectification',
                'description' => 'N/A',
                'stage' => '0',
            ],
            //patnet
            [
                'service_id' => '2',
                'title' => 'Prior Art',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Documentation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Drafting of Patent application',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Approval & Send quotation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Provisional Specification application filing',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Complete Specification application filing',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Request for early publication',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Examination',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Issuance of FER',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Response to FER',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Show cause Hearing',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Pre grant opposition',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Registration',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Post grant opposition',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Renewal',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Rectification',
                'description' => 'N/A',
                'stage' => '0',
            ],
            //Design
            [
                'service_id' => '3',
                'title' => 'Documentation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Documentation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Client Approval & Send quotation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Filing',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Examination',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Objection',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Show cause Hearing',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Registration',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '3',
                'title' => 'Renewal',
                'description' => 'N/A',
                'stage' => '0',
            ],
            //copyright
        ]);
    }
}
