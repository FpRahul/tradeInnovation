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
                'description' => 'Search for the trademark on the online portal',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Send quotation and payment verification',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Document verification and client approval',
                'description' => 'Search trademark on online portal to ensure the uniqueness',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Draft application on the portal',
                'description' => 'N/A',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Initial Examination process',
                'description' => 'N/A',
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
