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
                'title' => 'Payment confirmation',
                'description' => 'Payment confirmation for the sent quotation.',
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
                'title' => 'Examination Objected (Payment verifiction)',
                'description' => 'Notify client of examination objection and payment verification.',
                'stage' => '8',
            ],
            //
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation for Examination Objection',
                'description' => 'Cost for Processing Examination Objection Requests"',
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
                'title' => 'Payment Confirmation for trademark Opposition ',
                'description' => '(Payment confirmation) Additional charges related to the opposition process',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Notice Date',
                'description' => 'A notice has been received from the Trademark Registry',
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
                'title' => 'Trademark Registry Sent Notice to Opponent',
                'description' => 'Update the date and status when the trademark registry sent the notice to the opponent.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 45)',
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Applicant Evidence Submission status (Under rule 46)',
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 47)',
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Non compliance intimation to register',
                'description' => 'Update Department for Non-Compliance Intimation register',
                'stage' => '13',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Awaited for Hearing (Opposition) and  Inform Client',
                'description' => 'Add the court hearing date and inform the client about the scheduled session.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Decision on Hearing',
                'description' => 'Client decision on whether to proceed with the hearing process.',
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => 'Notifying client of hearing extra charge.',
                'description' => 'Client is informed about the additional charge for the hearing process.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Update the hearing charge status',
                'description' => 'Providing an update on the latest status of the hearing charge and any changes.',
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => ' Hearing (Opposition)',
                'description' => 'Update the hearing status / or next hearing date',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Written submission',
                'description' => 'Submit the signed agreement to the registrar for official registration and approval.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Status',
                'description' => 'Update the trademark status',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Post-Registration Actions',
                'description' => 'Manage tasks such as Renewal, Applicant Address change, Assignment/Registered User, Rectification, or IP Watch',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Rectification',
                'description' => 'Respond to opposition or challenge raised against a registered trademark',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Renewal',
                'description' => 'Initiate or manage the trademark renewal process',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Modification',
                'description' => 'Apply changes or updates to existing trademark details',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Assignment',
                'description' => 'Transfer ownership or rights of the trademark to another entity',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'IP Watch',
                'description' => 'Service to monitor if others are registering trademarks similar to yours',
                'stage' => '14',
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
                'description' => 'Client confirmation on the documents which is used to file the application.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Filing the application',
                'description' => 'Client wants to file an application for early publication.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Complete specification',
                'description' => 'A complete specification of the service',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Form 9',
                'description' => 'Request for early publication ',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Early publication',
                'description' => "Early publication requested via Form 9 to expedite the patent application's publication before 18 months.",
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Standard publication',
                'description' => "Application is published automatically after 18 months from the filing or priority date.",
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Form 18',
                'description' => 'Request for examination ',
                'stage' => '0',
            ],            
            [
                'service_id' => '2',
                'title' => 'FER (First Examination Report)',
                'description' => 'FER issued by the Patent Office with objections to be addressed.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'SER – Second Examination Report',
                'description' => 'Issued after FER if further objections remain.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Hearing Send Quotation',
                'description' => 'Send hearing quotation to the client.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Hearing Payment Verification',
                'description' => 'Check if hearing payment is received.',
                'stage' => '0',
            ],         
            [
                'service_id' => '2',
                'title' => 'Hearing',
                'description' => 'Conducted if objections persist after examination reports.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opposition',
                'description' => 'Opposition raised by a third party against the patent.',
                'stage' => '0',
            ],
            
        ]);
    }
}
