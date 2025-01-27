<?php

namespace App\Jobs;
use Illuminate\Queue\SerializesModels;
use App\Mail\forgotPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SentForgetPasswordmail implements ShouldQueue
{
   

    

    use Queueable, SerializesModels;
    public $updatePass;
    public $filePath;
    public $newPass;
 /**
     * Create a new job instance.
     *
     * @param $updatePass
     * @param $hashedToken
     * @param null $filePath
     */
    public function __construct($updatePass, $newPass,$filePath = null)
    {
        $this->updatePass = $updatePass;
        $this->filePath = $filePath;
        $this->newPass = $newPass;


    }
    public function handle(): void
    {   
        Mail::to($this->updatePass->email)->send(new forgotPassword($this->updatePass, $this->newPass , $this->filePath));
    }
}
