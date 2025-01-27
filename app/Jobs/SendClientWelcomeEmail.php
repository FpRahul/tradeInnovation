<?php
namespace App\Jobs;
use App\Mail\ClientWelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class SendClientWelcomeEmail implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $newClient;
    public $filePath;
    public function __construct($newClient, $filePath = null)
    {
        $this->newClient = $newClient;
        $this->filePath = $filePath;
    }
    public function handle(): void
    {   
        Mail::to($this->newClient->email)->send(new ClientWelcomeEmail($this->newClient, $this->filePath));
    }
}
