<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSubTask extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_sub_stages')->insert([
            [
                'stage_id' => '1',
                'title' => 'Search for the trademark on online portal',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '2',
                'title' => 'Send quotation with overall details to the client',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '2',
                'title' => 'Update Payment confirmation for the sent quotation',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '3',
                'title' => 'Check internally if all required documents are available for drafting the application.',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '3',
                'title' => 'Obtain acknowledgment from the client for the documents submitted with the application.',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '4',
                'title' => 'Draft application on the online portal',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '5',
                'title' => 'Update the formality check status on the filed application',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '6',
                'title' => 'Update the examination status',
                'sub_stage_id' => NULL,
            ],
            [
                'stage_id' => '6',
                'title' => 'Update the examination status',
                'sub_stage_id' => NULL,
            ]
        ]);
    }
}
