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
                'sub_service_id'=> 1,
                'description' => 'Search for the trademark on the online portal.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Send quotation',
                'sub_service_id'=> 1,
                'description' => 'Send Quotation to the client for the requested services.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment confirmation',
                'sub_service_id'=> 1,
                'description' => 'Payment confirmation for the sent quotation.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Document verification',
                'sub_service_id'=> 1,
                'description' => 'Verify documents internally which will be used to file the application.',
               
                'stage' => '0',
            ], 
            [
                'service_id' => '1',
                'title' => 'Document Draft',
                'sub_service_id'=> 1,
                'description' => 'Draft sent to the client for review and feedback to finalize the details.',
               
                'stage' => '0',
            ], 
            [
                'service_id' => '1',
                'title' => 'Client approval on documentation',
                'sub_service_id'=> 1,
                'description' => 'Client confirmation on the documents which is used to file the application',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Submit application',
                'sub_service_id'=> 1,
                'description' => 'Submit application on the portal',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Formality Check',
                'sub_service_id'=> 1,
                'description' => 'Update the formality check status.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Examination report',
                'sub_service_id'=> 4,
                'description' => 'Update the examination report status.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Objection',
                'sub_service_id'=> 4,
                'description' => 'Add reply on the objection within 30 days.',
               
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Objection reply status',
                'sub_service_id'=> 4,
                'description' => 'Add reply status from the only portal',
               
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Examination Objected (Payment verifiction)',
                'sub_service_id'=> 4,
                'description' => 'Notify client of examination objection and payment verification.',
               
                'stage' => '8',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation for Examination Objection',
                'sub_service_id'=> 4,
                'description' => 'Cost for Processing Examination Objection Requests"',
               
                'stage' => '8',

            ],
            [
                'service_id' => '1',
                'title' => 'Awaited for hearing (Objection)',
                'sub_service_id'=> 4,
                'description' => 'Add the hearing date provided by the court for the raised objection',
               
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Show cause hearing (Objection)',
                'sub_service_id'=> 4,
                'description' => 'Update the show cause hearing status / or next hearing date',
               
                'stage' => '8',
            ],
            [
                'service_id' => '1',
                'title' => 'Publish application',
                'sub_service_id'=> 1,
                'description' => 'Add the details of the published application in the system.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Opposition Status',
                'sub_service_id'=> 5,
                'description' => 'Update the opposition status.',
                'stage' => '0',
            ],
            [
                'service_id' => '1',
                'title' => 'Inform client and payment confirmation',
                'sub_service_id'=> 5,
                'description' => 'Inform client about the opposition and mark the payment status.',
                'stage' => '13',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation for trademark Opposition ',
                'sub_service_id'=> 5,
                'description' => '(Payment confirmation) Additional charges related to the opposition process',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Notice Date',
                'sub_service_id'=> 5,
                'description' => 'A notice has been received from the Trademark Registry',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Counter Statment',
                'sub_service_id'=> 5,
                'description' => 'Add the counter statment on the received oppostion.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Registry Sent Notice to Opponent',
                'sub_service_id'=> 5,
                'description' => 'Update the date and status when the trademark registry sent the notice to the opponent.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 45)',
                'sub_service_id'=> 5,
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Applicant Evidence Submission status (Under rule 46)',
                'sub_service_id'=> 5,
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 47)',
                'sub_service_id'=> 5,
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => 'Non compliance intimation to register',
                'sub_service_id'=> 5,
                'description' => 'Update Department for Non-Compliance Intimation register',
               
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => 'Interlocutory Petition (Opposition)',
                'sub_service_id'=> 5,
                'description' => 'An interlocutory petition is filed to request the court in case of delayed submission',
               
                'stage' => '13',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Awaited for Hearing (Opposition) and  Inform Client',
                'sub_service_id'=> 5,
                'description' => 'Add the court hearing date and inform the client about the scheduled session.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Decision on Hearing',
                'sub_service_id'=> 5,
                'description' => 'Client decision on whether to proceed with the hearing process.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => 'Notifying client of hearing extra charge.',
                'sub_service_id'=> 5,
                'description' => 'Client is informed about the additional charge for the hearing process.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Update the hearing charge status',
                'sub_service_id'=> 5,
                'description' => 'Providing an update on the latest status of the hearing charge and any changes.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => ' Hearing (Opposition)',
                'sub_service_id'=> 5,
                'description' => 'Update the hearing status / or next hearing date',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Written submission',
                'sub_service_id'=> 5,
                'description' => 'Submit the signed agreement to the registrar for official registration and approval.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Status',
                'sub_service_id'=> 5,
                'description' => 'Update the trademark status',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Post-Registration Actions',
                'sub_service_id'=> 0,
                'description' => 'Manage tasks such as Renewal, Applicant Address change, Assignment/Registered User, Rectification, or IP Watch',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Rectification',
                'sub_service_id'=> 7,
                'description' => 'Respond to opposition or challenge raised against a registered trademark',
               
                'stage' => '1',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Intimation for Additional Charges (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Notify the client regarding additional charges required to proceed with the rectification',
               
                'stage' => '14',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Payment confirmation on (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Confirm receipt of payment from the client for the rectification process',
               
                'stage' => '14',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Notice date (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'A notice has been received from the Trademark Registry',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Counter statement (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Add the counter statment on the received oppostion.',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Registry Sent Notice to Opponent (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Update the date and status when the trademark registry sent the notice to the opponent.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 45) (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Applicant Evidence Submission status (Under rule 46) (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Opponent Evidence Submission status (Under rule 47) (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Non compliance intimation to register (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Update Department for Non-Compliance Intimation register',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Interlocutory Petition (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'An interlocutory petition is filed to request the court in case of delayed submission',
               
                'stage' => '13',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Awaited for Hearing and  Inform Client (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Add the court hearing date and inform the client about the scheduled session.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Decision on Hearing (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Client decision on whether to proceed with the hearing process.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => 'Notifying client of hearing extra charge (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Client is informed about the additional charge for the hearing process.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Update the hearing charge status (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Providing an update on the latest status of the hearing charge and any changes.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '1',
                'title' => ' Hearing (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Update the hearing status / or next hearing date',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Written submission (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Submit the signed agreement to the registrar for official registration and approval.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Status (Rectification)',
                'sub_service_id'=> 7,
                'description' => 'Update the trademark status',
               
                'stage' => '13',
            ],
            [
                'service_id' => '1',
                'title' => 'Trademark Renewal',
                'sub_service_id'=> 2,
                'description' => 'Notify the client to proceed with their trademark renewal process',
               
                'stage' => '1',
            ],
            // [
            //     'service_id' => '1',
            //     'title' => 'Client Approval (Renewal)',
            // 'sub_service_id'=> ,//     
            // 'description' => 'Awaiting client confirmation to proceed with the trademark renewal',
            ///     
            // 'stage' => '14',
            // ],
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation (Renewal)',
                'sub_service_id'=> 2,
                'description' => 'Waiting for client payment confirmation',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Renewal Filed',
                'sub_service_id'=> 2,
                'description' => 'Filing the trademark renewal with the registry',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Intimation After Filing (Renewal)',
                'sub_service_id'=> 2,
                'description' => 'Client has been informed about the successful filing of the renewal application',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Applicant Address change',
                'sub_service_id'=> 6,
                'description' => 'Update the applicant address for a registered trademark with the Trademark Registry',
               
                'stage' => '1',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation (Applicant Address change)',
                'sub_service_id'=> 6,
                'description' => 'Waiting for client payment confirmation',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'send a query form (Applicant Address change)',
                'sub_service_id'=> 6,
                'description' => 'Query form sent to client to collect details for address change request',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Application Filed for Applicant Address Change',
                'sub_service_id'=> 6,
                'description' => 'Update the status of the address change filing process.',
               
                'stage' => '14',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Intimation After Filing Applicant Address Change',
                'sub_service_id'=> 6,
                'description' => 'Client has been informed about the successful filing of the address change application',
               
                'stage' => '14',
            ],
            
            [
                'service_id' => '1',
                'title' => 'Assignment/Registered User',
                'sub_service_id'=> 3,
                'description' => 'Updating client details and notifying about the transfer of trademark ownership to another entity.',
               
                'stage' => '1',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation (Assignment/Registered User)',
                'sub_service_id'=> 3,
                'description' => 'Confirming payment for trademark assignment and transferring ownership to another entity.',
               
                'stage' => '12',
            ],
            //
            [
                'service_id' => '1',
                'title' => 'Send Query Form (Assignment/Registered User)',
                'sub_service_id'=> 3,
                'description' => 'Query form sent to client to collect details',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Execution of deeds and affidavits (Assignment/Registered User)',
                'sub_service_id'=> 3,
                'description' => 'Executing deeds and affidavits to transfer trademark ownership.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Application Filed For (Assignment/Registered User)',
                'sub_service_id'=> 3,
                'description' => 'Update the status of the filing process.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Updation (Assignment/Registered User)',
                'sub_service_id'=> 3,
                'description' => 'Updating client details after trademark ownership transfer to another entity.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Intimation After Filing (Assignment/Registered User)',
                'sub_service_id'=> 3,
                'description' =>'Notifying the client after the trademark assignment filing, confirming the ownership transfer.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Expedite process',
                'sub_service_id'=> 8,
                'description' => 'Fast-track your trademark for quicker processing and approval.',
               
                'stage' => '1',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation (Expedite process)',
                'sub_service_id'=> 8,
                'description' => 'Confirming payment for expedited trademark processing and approval.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Application Filed For (Expedite process)',
                'sub_service_id'=> 8,
                'description' => 'Update the status of the filing process.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Intimation After Filing (Expedite process)',
                'sub_service_id'=> 8,
                'description' => 'Notifying the client after the trademark Expedite process filing.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'IP Watch',
                'sub_service_id'=> 9,
                'description' => 'Service to monitor if others are registering trademarks similar to yours',
               
                'stage' => '1',
            ],
            [
                'service_id' => '1',
                'title' => 'Payment Confirmation (IP Watch)',
                'sub_service_id'=> 9,
                'description' => 'Confirming payment for IP Watch.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'IP Watch Status Update',
                'sub_service_id'=> 9,
                'description' => 'Updating the client IP watch status and selected frequency (weekly/monthly/quarterly).',
               
                'stage' => '12',
            ],
            [
                'service_id' => '1',
                'title' => 'Client Intimation (IP Watch)',
                'sub_service_id'=> 9,
                'description' => 'Notifying the client about their IP watch status and selected frequency.',
               
                'stage' => '12',
            ],
            
            
            
            //patnet
            [
                'service_id' => '2',
                'title' => 'Send quotation',
                'sub_service_id'=> 10,
                'description' => 'Send Quotation to the client for the requested services.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Payment verification',
                'sub_service_id'=> 10,
                'description' => 'Payment verification for the sent quotation.',
               
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Prior Art',
                'sub_service_id'=> 10,
                'description' => 'Update the prior art status.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Document verification',
                'sub_service_id'=> 10,
                'description' => 'Verify documents internally which will be used to file the application.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Document Draft',
                'sub_service_id'=> 10,
                'description' => 'Draft sent to the client for review and feedback to finalize the details.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Client approval on documentation',
                'sub_service_id'=> 10,
                'description' => 'Client confirmation on the documents which is used to file the application.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Filing the application',
                'sub_service_id'=> 10,
                'description' => 'Client wants to file an application for early publication.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Complete specification',
                'sub_service_id'=> 10,
                'description' => 'A complete specification of the service',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Form 9',
                'sub_service_id'=> 10,
                'description' => 'Request for early publication ',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Early publication',
                'sub_service_id'=> 10,
                'description' => "Early publication requested via Form 9 to expedite the patent application's publication before 18 months.",
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Standard publication',
                'sub_service_id'=> 10,
                'description' => "Application is published automatically after 18 months from the filing or priority date.",
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Form 18',
                'sub_service_id'=> 10,
                'description' => 'Request for examination ',
                'stage' => '0',
            ],            
            [
                'service_id' => '2',
                'title' => 'FER (First Examination Report)',
                'sub_service_id'=> 10,
                'description' => 'FER issued by the Patent Office with objections to be addressed.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'SER – Second Examination Report',
                'sub_service_id'=> 10,
                'description' => 'Issued after FER if further objections remain.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Hearing Send Quotation',
                'sub_service_id'=> 10,
                'description' => 'Send hearing quotation to the client.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Hearing Payment Verification',
                'sub_service_id'=> 10,
                'description' => 'Check if hearing payment is received.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Awaiting Hearing Date',
                'sub_service_id'=> 10,
                'description' => 'Waiting for the patent office to schedule the hearing.',
                'stage' => '0',
            ],                    
            [
                'service_id' => '2',
                'title' => 'Show Cause Hearing',
                'sub_service_id'=> 10,
                'description' => 'Conducted if objections remain after the response to the examination report.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Pre-grant Opposition',
                'sub_service_id'=> 12,
                'description' => 'Anyone can oppose the patent before it is granted, based on certain legal reasons.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Send Quotation',
                'sub_service_id'=> 12,
                'description' => 'Send the quotation for opposition filing to the client.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opposition Payment Verification',
                'sub_service_id'=> 12,
                'description' => 'Verify if the payment for opposition filing has been received.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Notice Date',
                'sub_service_id'=> 12,
                'description' => 'A notice has been received from the Patent Registry',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Counter Statement',
                'sub_service_id'=> 12,
                'description' => 'Submit the counter statement in response to the received opposition.',
                'stage' => '0',
            ],            
            [
                'service_id' => '2',
                'title' => 'Non-Compliance Intimation to Controller/Registrar',
                'sub_service_id'=> 12,
                'description' => 'Notify the Controller about missed action or deadline.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opposition Awaiting Hearing Date',
                'sub_service_id'=> 12,
                'description' => 'Waiting for hearing date after opposition is filed.',
                'stage' => '0',
            ],  
            [
                'service_id' => '2',
                'title' => 'Opposition Client Confirmation',
                'sub_service_id'=> 12,
                'description' => 'Confirm with client whether to proceed with opposition.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Client Payment Verification',
                'sub_service_id'=> 12,
                'description' => 'Verify if client has made the required payment.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Hearing',
                'sub_service_id'=> 12,
                'description' => 'Attend the hearing scheduled by the Registrar.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Written Submission',
                'sub_service_id'=> 12,
                'description' => 'Submit a reply to the hearing or notice from the Patent Office.',
                'stage' => '0',
            ],
                         
            [
                'service_id' => '2',
                'title' => 'Patent Registry Sent Notice to Opponent',
                'sub_service_id'=> 12,
                'description' => 'Update the date and status when the patent registry sent the notice to the opponent.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opponent Evidence Submission status (Under rule 45)',
                'sub_service_id'=> 12,
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Applicant Evidence Submission status (Under rule 46)',
                'sub_service_id'=> 12,
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Opponent Evidence Submission status (Under rule 47)',
                'sub_service_id'=> 12,
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
                'stage' => '0',
            ],                                          
            
            [
                'service_id' => '2',
                'title' => 'Interlocutory Petition',
                'sub_service_id'=> 12,
                'description' => 'Covers disputes or hearings during patent opposition or examination.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Awaiting Hearing (Oppostion)',
                'sub_service_id'=> 12,
                'description' => 'Waiting for hearing date after opposition is filed.',
                'stage' => '0',
            ],  
            [
                'service_id' => '2',
                'title' => 'Opposition Client Confirmation',
                'sub_service_id'=> 12,
                'description' => 'Confirm with client whether to proceed with opposition.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Client Payment Verification',
                'sub_service_id'=> 12,
                'description' => 'Verify if client has made the required payment.',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Opposition Hearing',
                'sub_service_id'=> 12,
                'description' => 'Attend the hearing scheduled by the Registrar.',
                'stage' => '0',
            ],    
            [
                'service_id' => '2',
                'title' => 'Patent Status',
                'sub_service_id'=> 12,
                'description' => 'Complete/Refuse the patent registration ',
                'stage' => '0',
            ], 
            [
                'service_id' => '2',
                'title' => 'Post Registration Action',
                'sub_service_id'=> 0,
                'description' => 'Manage tasks such as Post Grant Opposition, Renewal, Applicant Address change, Assignment/Registered User.',
                'stage' => '0',
            ],
            [
                'service_id' => '2',
                'title' => 'Post Grant Opposition',
                'sub_service_id'=> 14,
                'description' => 'Respond to opposition or challenge raised against a registered patent.',
                'stage' => '1',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Intimation for Additional Charges (Post Grant Opposition)',
                'sub_service_id'=>14,
                'description' => 'Notify the client regarding additional charges required to proceed with the Post Grant Opposition',
               
                'stage' => '14',
            ],
            
            [
                'service_id' => '2',
                'title' => 'Payment confirmation on (Post Grant Opposition)',
                'sub_service_id'=>14,
                'description' => 'Confirm receipt of payment from the client for the Post Grant Opposition process',
               
                'stage' => '14',
            ],
            
            [
                'service_id' => '2',
                'title' => 'Notice date (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'A notice has been received from the patent Registry',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Counter statement (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Add the counter statment on the received oppostion.',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Patent Registry Sent Notice to Opponent (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Update the date and status when the patent registry sent the notice to the opponent.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Opponent Evidence Submission status (Under rule 45) (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Updates the status of the opponent evidence submission under rule 45.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Applicant Evidence Submission status (Under rule 46) (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Updates the status of the applicant evidence submission under rule 46.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Opponent Evidence Submission status (Under rule 47) (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Updates the status of the Opponent evidence submission under rule 47.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Non compliance intimation to register (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Update Department for Non-Compliance Intimation register',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Interlocutory Petition (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'An interlocutory petition is filed to request the court in case of delayed submission',
               
                'stage' => '13',
            ],
            
            [
                'service_id' => '2',
                'title' => 'Awaited for Hearing and  Inform Client (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Add the court hearing date and inform the client about the scheduled session.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Decision on Hearing (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Client decision on whether to proceed with the hearing process.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '2',
                'title' => 'Notifying client of hearing extra charge (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Client is informed about the additional charge for the hearing process.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Update the hearing charge status (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Providing an update on the latest status of the hearing charge and any changes.',
               
                'stage' => '13',
            ],

            [
                'service_id' => '2',
                'title' => ' Hearing (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Update the hearing status / or next hearing date',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Written submission (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Submit the signed agreement to the registrar for official registration and approval.',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Patent Status (Post Grant Opposition)',
                'sub_service_id'=> 14,
                'description' => 'Update the patent status',
               
                'stage' => '13',
            ],
            [
                'service_id' => '2',
                'title' => 'Patent Renewal',
                'sub_service_id'=> 11,
                'description' => 'Notify the client to proceed with their patent renewal process',               
                'stage' => '1',
            ],            
            [
                'service_id' => '2',
                'title' => 'Payment Confirmation (Renewal)',
                'sub_service_id'=> 11,
                'description' => 'Waiting for client payment confirmation',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Renewal Filed',
                'sub_service_id'=> 11,
                'description' => 'Filing the patent renewal with the registry',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Intimation After Filing (Renewal)',
                'sub_service_id'=> 11,
                'description' => 'Client has been informed about the successful filing of the renewal application',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Applicant Address change',
                'sub_service_id'=> 16,
                'description' => 'Update the applicant address for a registered patent with the patent Registry',
               
                'stage' => '1',
            ],
            [
                'service_id' => '2',
                'title' => 'Payment Confirmation (Applicant Address change)',
                'sub_service_id'=> 16,
                'description' => 'Waiting for client payment confirmation',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'send a query form (Applicant Address change)',
                'sub_service_id'=> 16,
                'description' => 'Query form sent to client to collect details for address change request',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Application Filed for Applicant Address Change',
                'sub_service_id'=> 16,
                'description' => 'Update the status of the address change filing process.',
               
                'stage' => '14',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Intimation After Filing Applicant Address Change',
                'sub_service_id'=> 16,
                'description' => 'Client has been informed about the successful filing of the address change application',
               
                'stage' => '14',
            ],
            
            [
                'service_id' => '2',
                'title' => 'Assignment/Registered User',
                'sub_service_id'=> 15,
                'description' => 'Updating client details and notifying about the transfer of patent ownership to another entity.',
               
                'stage' => '1',
            ],
            [
                'service_id' => '2',
                'title' => 'Payment Confirmation (Assignment/Registered User)',
                'sub_service_id'=> 15,
                'description' => 'Confirming payment for patent assignment and transferring ownership to another entity.',
               
                'stage' => '12',
            ],
            //
            [
                'service_id' => '2',
                'title' => 'Send Query Form (Assignment/Registered User)',
                'sub_service_id'=> 15,
                'description' => 'Query form sent to client to collect details',
               
                'stage' => '12',
            ],
            [
                'service_id' => '2',
                'title' => 'Execution of deeds and affidavits (Assignment/Registered User)',
                'sub_service_id'=> 15,
                'description' => 'Executing deeds and affidavits to transfer patent ownership.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '2',
                'title' => 'Application Filed For (Assignment/Registered User)',
                'sub_service_id'=> 15,
                'description' => 'Update the status of the filing process.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Updation (Assignment/Registered User)',
                'sub_service_id'=> 15,
                'description' => 'Updating client details after patent ownership transfer to another entity.',
               
                'stage' => '12',
            ],
            [
                'service_id' => '2',
                'title' => 'Client Intimation After Filing (Assignment/Registered User)',
                'sub_service_id'=> 15,
                'description' =>'Notifying the client after the patent assignment filing, confirming the ownership transfer.',
               
                'stage' => '12',
            ],
        ]);
    }
}
