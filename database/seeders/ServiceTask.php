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
                'description' => 'Verify documents internally which will be used to file the application.',
                'stage' => '0',
            ], 
            [
                'service_id' => '1',
                'title' => 'Document Draft',
                'description' => 'Draft sent to the client for review and feedback to finalize the details.',
                'stage' => '0',
            ], 
            [
                'service_id' => '1',
                'title' => 'Client approval on documentation',
                'description' => 'Client confirmation on the documents which is used to file the application',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Submit application',
                'description' => 'Submit application on the portal',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Formality Check',
                'description' => 'Update the formality check status.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Examination report',
                'description' => 'Update the examination report status.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Objection',
                'description' => 'Add reply on the objection within 30 days.',
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Objection reply status',
                'description' => 'Add reply status from the only portal',
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Awaited for hearing (Objection)',
                'description' => 'Add the hearing date provided by the court for the raised objection',
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Show cause hearing (Objection)',
                'description' => 'Update the show cause hearing status / or next hearing date',
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Publish application',
                'description' => 'Add the details of the published application in the system.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Opposition Status',
                'description' => 'Update the opposition status.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Inform client and payment confirmation',
                'description' => 'Inform client about the opposition and mark the payment status.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Counter Statment',
                'description' => 'Add the counter statment on the received oppostion.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Submit Evidence',
                'description' => 'Submitt the evidence on the received opposition.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Awaited for hearing (Opposition)',
                'description' => 'Add the hearing date provided by the court for the raised opposition',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Show cause hearing (Opposition)',
                'description' => 'Update the show cause hearing status / or next hearing date',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Published',
                'description' => 'Complete the trademark registration ',
                'stage' => '13',
            ],
            //patnet
            [
                'service_id' => '2',
                'title' => 'Send quotation',
                'description' => 'Send Quotation to the client for the requested services.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Payment verification',
                'description' => 'Payment verification for the sent quotation.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Prior Art',
                'description' => 'Update the prior art status.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Document verification',
                'description' => 'Verify documents internally which will be used to file the application.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Document Draft',
                'description' => 'Draft sent to the client for review and feedback to finalize the details.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Client approval on documentation',
                'description' => 'Client confirmation on the documents which is used to file the application',
                'stage' => '0',
            ],
            
        ]);
    }
}
