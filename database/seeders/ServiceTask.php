<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTask extends Seeder
{
    
    public function run(): void
    {
        DB::table('service_stages')->insert([
            [
                'service_id' => '1',
                'title' => 'Search trademark',
                'description' => 'Search for the trademark on the online portal.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Send quotation',
                'description' => 'Send Quotation to the client for the requested services.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment verification',
                'description' => 'Payment verification for the sent quotation.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Document verification',
                'description' => 'Verify documents internally which will be used to file application.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Client approval for documentation',
                'description' => 'Take approval from the client.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Draft application',
                'description' => 'Draft application on the portal',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Initial Examination',
                'description' => 'Update the initial examination status.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Formality Check',
                'description' => 'Update the formality check status.',
                'stage' => '0',
            ],
            //patnet
            [
                'service_id' => '2',
                'title' => 'Payment confirmation',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Prior Art',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Document verification and client approval',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Drafting application',
                'description' => 'N/A',
                'stage' => '0',
            ]
        ]);
    }
}
