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
                'title' => 'Send quotation',
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
                'title' => 'Published',
                'description' => 'N/A',
                'stage' => '0',
            ],
        ]);
    }
}
