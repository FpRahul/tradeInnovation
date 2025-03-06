<?php

namespace App\Jobs;
use App\Mail\CommonDraftSend;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;


class CommanDraftSend implements ShouldQueue
{
    use Queueable, SerializesModels; 

    /**
     * Create a new job instance.
     */
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
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->clientEmail)->send(new CommonDraftSend(
            $this->subject, 
            $this->service, 
            $this->remark, 
            $this->filePaths,
            $this->clientName, 
            $this->clientEmail,
            $this->userName
        ));

    }
}
