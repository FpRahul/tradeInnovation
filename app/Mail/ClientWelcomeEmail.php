<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ClientWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $newClient;
    public $filePath;
    public $newClientDetails;

    
    public function __construct($newClient , $newClientDetails,$filePath = null)
    {
        $this->newClient = $newClient;
        $this->filePath = $filePath;
        $this->newClientDetails = $newClientDetails;

    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Welcome {$this->newClient->name}!",
        );
    }

    
    public function content(): Content
    {
        return new Content(
            view: 'emails.client_welcome',
            with: [
                'client' => $this->newClient,
                'client' => $this->newClient,
                'newClientDetails' => $this->newClientDetails
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
        if($this->filePath){
            $attachments[] = new Attachment($this->filePath);
        }
        return $attachments;
    }
}
