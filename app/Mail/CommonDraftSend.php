<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommonDraftSend extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $service;
    public $leadID;
    public $companyName;
    public $filePaths;
    public $clientName;
    public $clientEmail;
    public $clientMobile;
    public $trademarkName;
    public $assignApplicationNumber;


    public function __construct($subject,$service,$leadID,$companyName, $filePaths,$clientName,$clientEmail , $clientMobile , $trademarkName , $assignApplicationNumber)
    {
        $this->subject = $subject;
        $this->service = $service;
        $this->companyName = $companyName;
        $this->leadID  = $leadID;
        $this->filePaths = $filePaths;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->clientMobile = $clientMobile;
        $this->trademarkName = $trademarkName;
        $this->assignApplicationNumber = $assignApplicationNumber;



    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }
 
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        
            return new Content(
                view: 'emails.task_draft_comman',
                with: [
                    'subject' => $this->subject,
                    'service' => $this->service,
                    'companyName' =>  $this->companyName,
                    'leadID' => $this->leadID,
                    'filePaths' => $this->filePaths,
                    'clientName' => $this->clientName,
                    'clientEmail' => $this->clientEmail,
                    'clientMobile' => $this->clientMobile,
                    'trademarkName' => $this->trademarkName,
                    'assignApplicationNumber' => $this->assignApplicationNumber
                ]
            );
    
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if ($this->filePaths) {
            foreach ($this->filePaths as $file) {
                $filePath = public_path('uploads/leads/' . $this->leadID . '/' . $file);
                if (file_exists($filePath)) {
                    $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($filePath)->as($file);
                }
            }
        }
    
        return $attachments;
    }
}
