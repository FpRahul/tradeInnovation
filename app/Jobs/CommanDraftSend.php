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
    public $subject;
    public $service;
    public $leadID;
    public $companyName;
    public $filePaths;
    public $clientName;
    public $clientEmail;
    public $clientMobile;
    public $trademarkName;
    public $assignApplicationNumber;


    
    public function __construct($subject,$service,$leadID,$companyName,$filePaths,$clientName,$clientEmail,$clientMobile, $trademarkName, $assignApplicationNumber)
    {  
        
        $this->subject = $subject;
        $this->service = $service;
        $this->leadID  = $leadID;
        $this->companyName = $companyName;
        $this->filePaths = $filePaths;
        $this->clientName = $clientName;
        $this->clientEmail = $clientEmail;
        $this->clientMobile = $clientMobile;
        $this->trademarkName = $trademarkName;
        $this->assignApplicationNumber = $assignApplicationNumber;

 

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->clientEmail)->send(new CommonDraftSend(
            $this->subject, 
            $this->service,
            $this->leadID, 
            $this->companyName,
            $this->filePaths,
            $this->clientName, 
            $this->clientEmail,
            $this->clientMobile,
            $this->trademarkName,
            $this->assignApplicationNumber

        ));

    }
}
