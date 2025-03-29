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
    public $gst_amount;
    public $total;
    public $clientName;
    public $clientEmail;
    public $userName;
    public $clientMobile;
    public $clientCompany;
    public $serviceName;
    public $subServiceName;

    public function __construct($subject,$service,$service_price,$govt_price,$gst_amount,$total,$clientName,$clientEmail,$userName,$clientMobile,$clientCompany,$serviceName,$subServiceName)
    {
        $this->subject = $subject;
        $this->service = $service;
        $this->service_price  = $service_price;
        $this->govt_price = $govt_price;
        $this->gst_amount = $gst_amount;
        $this->total = $total;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->userName = $userName;
        $this->clientMobile = $clientMobile;
        $this->clientCompany = $clientCompany;
        $this->serviceName = $serviceName;
        $this->subServiceName = $subServiceName;


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
                    'gst_amount' => $this->gst_amount,
                    'total' => $this->total,
                    'clientName' => $this->clientName,
                    'clientEmail' => $this->clientEmail,
                    'userName' => $this->userName,
                    'clientMobile' => $this->clientMobile,
                    'clientCompany' => $this->clientCompany,
                    'serviceName' => $this->serviceName,
                    'subServiceName' => $this->subServiceName,
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
