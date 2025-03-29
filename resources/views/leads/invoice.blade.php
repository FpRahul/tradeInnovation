@extends('layouts.default')
@section('content')
<div class="w-full bg-[#fff] shadow-[] border-[1px] border-[#f2f2f2] p-[15px] pt-[50px] rounded-[10px]">
    <table border="0" cellspacing="0" cellpadding="0" class="w-full max-w-[1400px] m-auto" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
        <tr>
           <td style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 60px 30px 0; text-align: center;">
              <img src="{{asset('assets/images/logo.png')}}" alt="Your Logo" style="max-width: 200px; margin:0 auto 40px;">
              <h1 style="color: #000; font-size: 20px; margin: 0; font-weight: bold;">Welcome to Our Platform!</h1>
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
              <div class="mb-8">
                 <table class="w-[100%] border-[1px] border-[#f2f2f2]" >
                    <tr>
                       <td class="">
                          <table class="w-full">
                             <tr>
                                <th class="px-[10px] py-[10px] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">
                                   Name:
                                </th>
  
                                <th class="px-[10px] py-[10px] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">
                                   Mobile:
                                </th>
  
                                <th class="px-[10px] py-[10px] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">
                                   Email:
                                </th>
  
                                <th class="px-[10px] py-[10px] text-[13px] font-[600] text-[#000] border-b-[1px] border-b-[#f2f2f2] ">
                                   Company Name:
                                </th>
                             </tr>
                                                            
                                <tr>
                                   <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$leadDetails->client_name}}</td>
                                   <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$leadDetails->mobile_number}}</td>
                                   <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$leadDetails->email}}</td>
                                   <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2]">{{$leadDetails->company_name }}</td>
                                </tr>
                                                       
                          </table>
                       </td>                       
                    </tr>
                 </table>
              </div>
  
              <div class="pb-[40px]">
              <table class="w-[100%] border-[1px] border-[#f2f2f2]" >
                
                 <tr>
                    <td>
                       <table class="w-full">
                          <tr>
                             <th class="px-[10px] py-[10px] text-[13px] font-[600] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">Services</th>
                             <th class="px-[10px] py-[10px] text-[13px] font-[600] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">Sub-Services</th>
                          </tr>
                          @if(!empty($leadDetails->leadTasks   ))
                             @foreach ($leadDetails->leadTasks as $serK => $serV)   
                                                      
                                 <tr>
                                    <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$serV->services->serviceName}}</td>
                                    <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$serV->subService->subServiceName}}</td>
                                 </tr>    
                             @endforeach
                          @endif                    
                       </table>
                    </td>
                 </tr>
              </table>
              </div>
  
           </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-collapse: collapse; border: 1px solid #ddd; background-color: #fafafa;">
                 <tr>
                    <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Service:</td>
                    <td id='mailService' width="50%" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;"> </td>
                 </tr>
                 <tr>
                    <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Service Price:</td>
                    <td width="50%" id="mailServicePrice" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">Not Updated</td>
                 </tr>
                 <tr>
                    <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Government Price:</td>
                    <td width="50%" id="mailGovtPrice" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">Not Updated</td>
                 </tr>
                 <tr>
                    <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">GST</td>
                    <td width="50%" id="mailGst" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">Not Updated</td>
                 </tr>
                 <tr>
                    <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Total</td>
                    <td width="50%" id="mailTotal" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">Not Updated</td>
                 </tr>
              </table>
              <p style="font-size: 16px; text-align: center; line-height: 1.2; margin: 35px 0 0; color: #555;">
                 Thank you for choosing us! <br>
              </p>
              <p style="text-align: center; margin: 15px 0 0;">
                 Best regards,<br>
                 The Support Team
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

    <div class="mt-[20px] mb-[20px] flex gap-[10px] justify-center">
        <button class="px-[15px] py-[10px] bg-[#13103a] text-[15px] text-[#fff] rounded-[5px]">Save & Download</button>
        <button class="px-[15px] py-[10px] bg-[#13103a] text-[15px] text-[#fff] rounded-[5px]">Save & Email</button>
    </div>

</div>
<script>
 $(document).ready(function() {
        function initializeDatePicker(element) {
            element.daterangepicker({
                singleDatePicker: true,
                opens: 'right',
                locale: {
                    format: 'DD MMM YYYY'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
            });
        }

        initializeDatePicker($('.daterangepicker-startDate'));

        $(document).on('focus', '.daterangepicker-startDate', function() {
            if (!$(this).data('daterangepicker')) {
                initializeDatePicker($(this));
            }
        });
    });
</script>
@stop