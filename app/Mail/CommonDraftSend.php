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

    public $subject; // single varabel define the what we register User Or client or associate
    public $service;
    public $remark;
    public $filePaths;
    public $clientName;
    public $clientEmail;
    public $userName;


    public function __construct($subject,$service,$remark,$filePaths,$clientName,$clientEmail,$userName)
    {
        $this->subject = $subject;
        $this->service = $service;
        $this->remark  = $remark;
        $this->filePaths = $filePaths;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->userName = $userName;


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
                    'remark' => $this->remark,
                    'filePaths' => $this->filePaths,
                    'clientName' => $this->clientName,
                    'clientEmail' => $this->clientEmail,
                    'userName' => $this->userName,
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
        return [];
    }
}
