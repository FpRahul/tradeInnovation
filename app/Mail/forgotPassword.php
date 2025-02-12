<?php

namespace App\Mail; 

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;


class forgotPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $updatePass;
    public $filePath;
    public $newPass;


    /**
     * Create a new message instance.
     *
     * @param $updatePass
     * @param $hashedToken
     * @param $filePath
     */
    public function __construct($updatePass,$newPass,$filePath)
    {
        $this->updatePass = $updatePass;
        $this->filePath = $filePath;
        $this->newPass = $newPass;

    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Forgot Password {$this->updatePass->name}",
        );
    }

    
    public function content(): Content
    {
        return new Content(
           view: 'emails.forgetPassword_template',
            with: [
                'updatePass' => $this->updatePass,
                'newPass' => $this->newPass
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
