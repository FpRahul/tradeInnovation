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

    public $userOrClient;  
    public $filePath;
    public $randomNumber;
    public $type;
    public function __construct($userOrClient , $randomNumber,$filePath, $type)
    {
        $this->userOrClient = $userOrClient;
        $this->filePath = $filePath;
        $this->randomNumber = $randomNumber;
        $this->type = $type;
    }
    public function envelope(): Envelope
    { 
        $subject = "Welcome {$this->userOrClient->name}!";
        // Adjust subject based on type (client, user, associate)
        if ($this->type == 'Client') {
            $subject = "Welcome {$this->userOrClient->name} to our platform!";
        } elseif ($this->type == 'User') {
            $subject = "Welcome User {$this->userOrClient->name}!";
        } elseif ($this->type == 'Associate') {
            $subject = "Welcome Associate {$this->userOrClient->name}!";
        }
        return new Envelope(
            subject: $subject,
        );
    }
    public function content(): Content
    {   
        if($this->type == 'Client'){
            return new Content(
                view: 'emails.client_welcome',
                with: [
                    'client' => $this->userOrClient,
                    'randomNumber' => $this->randomNumber
                ]
            );
        }else if($this->type == 'User'){
            return new Content(
                view: 'emails.user_register',
                with: [
                    'user' => $this->userOrClient,
                    'randomNumber' => $this->randomNumber
                ]
            );
        }else if($this->type == 'Associate'){
            return new Content(
                view: 'emails.associate_register',
                with: [
                    'associate' => $this->userOrClient,
                    'randomNumber' => $this->randomNumber
                ]
            );
        }
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
