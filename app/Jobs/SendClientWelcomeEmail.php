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
    public $userOrClient; // single varabel define the what we register User Or client or associate
    public $filePath;
    public $randomNumber;
    public $type;

    public function __construct($userOrClient, $randomNumber, $filePath, $type)
    {   
        $this->userOrClient = $userOrClient;
        $this->filePath = $filePath;
        $this->randomNumber  = $randomNumber;
        $this->type = $type;
    }
    public function handle(): void
    {   
        
        if($this->type == 'Client'){
            Mail::to($this->userOrClient->email)->send(new ClientWelcomeEmail($this->userOrClient, $this->randomNumber,$this->filePath, 'Client'));
        }
        else if($this->type == 'User'){
            Mail::to($this->userOrClient->email)->send(new ClientWelcomeEmail($this->userOrClient, $this->randomNumber,$this->filePath,'User'));
        }
        else if($this->type == 'Associate'){
            Mail::to($this->userOrClient->email)->send(new ClientWelcomeEmail($this->userOrClient, $this->randomNumber,$this->filePath,'Associate'));
        }else {
            Log::error('User or Client is null in SendClientWelcomeEmail job.');
        }
    }
}
