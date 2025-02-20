<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskCommanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject; 
    public $service;
    public $service_price;
    public $govt_price;
    public $clientName;
    public $clientEmail;
    public $userName;


    public function __construct($subject,$service,$service_price,$govt_price,$clientName,$clientEmail,$userName)
    {
        $this->subject = $subject;
        $this->service = $service;
        $this->service_price  = $service_price;
        $this->govt_price = $govt_price;
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
                view: 'emails.task_comman',
                with: [
                    'subject' => $this->subject,
                    'service' => $this->service,
                    'service_price' => $this->service_price,
                    'govt_price' => $this->govt_price,
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
