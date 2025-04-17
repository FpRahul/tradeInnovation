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
                'title' => 'Client Intimation for Additional Charges (Rectification)',
                'description' => 'Notify the client regarding additional charges required to proceed with the rectification',
                'stage' => '14',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Payment confirmation on (Rectification)',
                'description' => 'Confirm receipt of payment from the client for the rectification process',
                'stage' => '14',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Notice date (Rectification)',
                'description' => 'A notice has been received from the Trademark Registry',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Counter statement (Rectification)',
                'description' => 'Add the counter statment on the received oppostion.',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Registry Sent Notice to Opponent (Rectification)',
                'description' => 'Update the date and status when the trademark registry sent the notice to the opponent.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 45) (Rectification)',
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Applicant Evidence Submission status (Under rule 46) (Rectification)',
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 47) (Rectification)',
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Non compliance intimation to register (Rectification)',
                'description' => 'Update Department for Non-Compliance Intimation register',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Interlocutory Petition (Rectification)',
                'description' => 'Update Department for Non-Compliance Intimation register',
                'stage' => '13',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Awaited for Hearing (Opposition) and  Inform Client (Rectification)',
                'description' => 'Add the court hearing date and inform the client about the scheduled session.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Decision on Hearing (Rectification)',
                'description' => 'Client decision on whether to proceed with the hearing process.',
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => 'Notifying client of hearing extra charge (Rectification)',
                'description' => 'Client is informed about the additional charge for the hearing process.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Update the hearing charge status (Rectification)',
                'description' => 'Providing an update on the latest status of the hearing charge and any changes.',
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => ' Hearing (Rectification)',
                'description' => 'Update the hearing status / or next hearing date',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Written submission (Rectification)',
                'description' => 'Submit the signed agreement to the registrar for official registration and approval.',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Status (Rectification)',
                'description' => 'Update the trademark status',
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Renewal',
                'description' => 'Notify the client to proceed with their trademark renewal process',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Approval (Renewal)',
                'description' => 'Awaiting client confirmation to proceed with the trademark renewal',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation (Renewal)',
                'description' => 'Waiting for client payment confirmation',
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Renewal Filed',
                'description' => 'Filing the trademark renewal with the registry',
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
                'title' => 'Awaiting Hearing Date',
                'description' => 'Waiting for the patent office to schedule the hearing.',
                'stage' => '0',
            ],                    
            [
                'service_id' => '2',
                'title' => 'Show Cause Hearing',
                'description' => 'Conducted if objections remain after the response to the examination report.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Pre-grant Opposition',
                'description' => 'Anyone can oppose the patent before it is granted, based on certain legal reasons.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Send Quotation',
                'description' => 'Send the quotation for opposition filing to the client.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opposition Payment Verification',
                'description' => 'Verify if the payment for opposition filing has been received.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Notice Date',
                'description' => 'A notice has been received from the Patent Registry',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Counter Statement',
                'description' => 'Submit the counter statement in response to the received opposition.',
                'stage' => '0',
            ],            
            [
                'service_id' => '2',
                'title' => 'Non-Compliance Intimation to Controller/Registrar',
                'description' => 'Notify the Controller about missed action or deadline.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opposition Awaiting Hearing Date',
                'description' => 'Waiting for hearing date after opposition is filed.',
                'stage' => '0',
            ],  
            [
                'service_id' => '2',
                'title' => 'Opposition Client Confirmation',
                'description' => 'Confirm with client whether to proceed with opposition.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Client Payment Verification',
                'description' => 'Verify if client has made the required payment.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Hearing',
                'description' => 'Attend the hearing scheduled by the Registrar.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Written Submission',
                'description' => 'Submit a reply to the hearing or notice from the Patent Office.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Patent Status',
                'description' => 'Complete/Refuse the patent registration ',
                'stage' => '0',
            ],              
            [
                'service_id' => '2',
                'title' => 'Patent Registry Sent Notice to Opponent',
                'description' => 'Update the date and status when the patent registry sent the notice to the opponent.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opponent Evidence Submission status (Under rule 45)',
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Applicant Evidence Submission status (Under rule 46)',
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opponent Evidence Submission status (Under rule 47)',
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
                'stage' => '0',
            ],                                          
            [
                'service_id' => '2',
                'title' => 'Post Registration Action',
                'description' => 'Includes actions like renewal, opposition, or amendments after the patent is granted.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Interlocutory Petition',
                'description' => 'Covers disputes or hearings during patent opposition or examination.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Awaiting Hearing (Oppostion)',
                'description' => 'Waiting for hearing date after opposition is filed.',
                'stage' => '0',
            ],  
            [
                'service_id' => '2',
                'title' => 'Opposition Client Confirmation',
                'description' => 'Confirm with client whether to proceed with opposition.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Client Payment Verification',
                'description' => 'Verify if client has made the required payment.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Hearing',
                'description' => 'Attend the hearing scheduled by the Registrar.',
                'stage' => '0',
            ],    
            
        ]);
    }
}
