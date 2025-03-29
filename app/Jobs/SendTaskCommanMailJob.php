<?php

namespace App\Jobs;
use App\Mail\TaskCommanMail;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class SendTaskCommanMailJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $subject; // single varabel define the what we register User Or client or associate
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
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->clientEmail)->send(new TaskCommanMail(
            $this->subject, 
            $this->service, 
            $this->service_price, 
            $this->govt_price,
            $this->gst_amount, 
            $this->total,
            $this->clientName, 
            $this->clientEmail,
            $this->userName,
            $this->clientMobile,
            $this->clientCompany,
            $this->serviceName,
            $this->subServiceName
        ));

    }
}
