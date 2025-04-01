<div class="w-full bg-[#fff] shadow-[] border-[1px] border-[#f2f2f2] p-[15px] pt-[20px] rounded-[10px]">
    <table border="0" cellspacing="0" cellpadding="0" class="w-full max-w-[1400px] m-auto" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
            <tr>
            <td style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 20px 30px 0; text-align: center;">
                <img src="{{asset('assets/images/logo.png')}}" alt="Your Logo" style="max-width: 200px; margin:0 auto 20px;">
                <h1 style="color: #000; font-size: 20px; margin: 0 0 20px; font-weight: bold;">Welcome to Our Platform!</h1>
            </td>
            </tr>
            <tr>
            <td style="padding: 15px 15px; text-align: left;">
                <p style="font-size: 16px; line-height: 1.8; margin: 0 0 10px; color: #555;">
                    Hello <strong id="mailClientName">{{$leadDetails->client_name}}</strong>,
                </p>
                <p style="font-size: 16px; line-height: 1.8; margin: 0 0 20px; color: #555;">
                    Your service request for <strong></strong> has been processed. Below are the details of your service and the associated pricing:
                </p>
    
                {{-- Task Invoice --}}
                <div class="pb-[25px]">
                    <h2 class="text-[#000] text-[17px] font-[600] mb-[15px] text-[center]">Client Details</h2>
                    <div class="mb-8 overflow-auto">
                        <table class="w-[100%] 1border-[1px] 1border-[#f2f2f2]">
                            <tr>
                            <td class="">
                                <table class="w-full">
                                    <tr>
                                        <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">
                                        Name:
                                        </th>
        
                                        <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">
                                        Mobile:
                                        </th>
        
                                        <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">
                                        Email:
                                        </th>
        
                                        <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2] ">
                                        Company Name:
                                        </th>                                
                                    </tr>
                                                                    
                                        <tr>
                                        <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$leadDetails->client_name}}</td>
                                        <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$leadDetails->mobile_number}}</td>
                                        <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$leadDetails->email}}</td>
                                        <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2]">{{$leadDetails->company_name }}</td>
                                        </tr>
                                                                
                                </table>
                            </td>                       
                            </tr>
                        </table>
                    </div>
    
                <div class="pb-[40px] overflow-auto">
                    <table class="w-[100%] 1border-[1px] 1border-[#f2f2f2]" >                
                        <tr>
                            <td>
                            <table class="w-full">
                                <tr>
                                    <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]">Services</th>
                                    <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]">Sub-Services</th>
                                    <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]">Service Price</th>
                                    <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]">Govt. Price</th>
                                    <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]">GST</th>
                                    <th class="px-[16px] py-[16px] bg-[#f2f2f2] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]">Total Price</th>
                                </tr>
                                @if(!empty($leadDetails->leadTasks   ))
                                    @foreach ($leadDetails->leadTasks as $serK => $serV)                            
                                            @php
                                                $payment = $serV->payment->last();   
                                            @endphp
                                            
                                        <tr>
                                            <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$serV->services->serviceName}}</td>
                                            <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$serV->subService->subServiceName}}</td>
                                            <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$payment->service_price}}</td>
                                            <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$payment->govt_price}}</td>
                                            <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$payment->gst}}</td>
                                            <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] 1border-r-[1px] 1border-r-[#f2f2f2]">{{$payment->total}}</td>
                                        </tr>    
                                    @endforeach
                                @endif                    
                            </table>
                            </td>
                        </tr>
                    </table>
                </div>

                    <h2 class="text-[#000] text-[17px] font-[600] mb-[15px] text-[center]">Account Details</h2>
                    <div class="mb-8 flex flex-col gap-[5px] border-[1px] border-[#f2f2f2] p-[20px]">
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">Holder Name :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->acc_holder_name}}</p>
                        </div>
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">Account Number :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->account_number}}</p>
                        </div>
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">IFSC Code :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->ifsc_code}}</p>
                        </div>
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">Bank Name :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->bank_name}}</p>
                        </div>
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">Branch :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->branch_name}}</p>
                        </div>    
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">Swift Code :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->swift_code}}</p>
                        </div>
                        <div class="mb-[5px] flex gap-[15px]">
                            <label class="min-w-[130px] text-[15px] text-[#000] font-[600]">UPI :</label>
                            <p class="text-[15px] text-[#000] font-[400]">{{ $leadDetails->LeadFirm->upi_id}}</p>
                        </div>                 
                    </div>
    
            </div>
            
                <p style="font-size: 16px; text-align: center; line-height: 1.2; margin: 35px 0 0; color: #555;">Thank you for choosing us! <br></p>
                <p style="text-align: center; margin: 15px 0 0;">Best regards,<br> The Support Team
                </p>
            </td>
            </tr>
            <!-- Footer Section -->
            <tr>
            <td style="background-color: #1c194d; text-align: center; padding: 15px; font-size: 12px; color: #ffffff;">
                © 2025 Your Company Name. All rights reserved.
                <a href="https://yourcompany.com" style="color: #fff; text-decoration: none;">Visit Our Website</a>
            </td>
            </tr>
    </table>
</div>