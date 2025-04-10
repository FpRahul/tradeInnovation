@extends('layouts.default')
@section('content')
<div class="w-full bg-[#fff] shadow-[] border-[1px] border-[#f2f2f2] p-[15px] pt-[20px] rounded-[10px]">
   <table border="0" cellspacing="0" cellpadding="0" class="w-full max-w-[1400px] m-auto" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
        <tr>
          

           <td>
            <table class="w-full">
                <tr>
                    <td valign="top" class="w-[33%]">&nbsp;</td>
                    <td valign="top" class="w-[33%]" style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 20px 30px 0; text-align: center;">
                     <img src="{{asset('assets/images/logo.png')}}" alt="Your Logo" style="max-width: 200px; margin:0 auto 20px;">
                     <h1 style="color: #000; font-size: 20px; margin: 0 0 20px; font-weight: bold;">Welcome to Our Platform!</h1>
                  </td>
                    <td valign="top" class="w-[33%] text-right">
                        <div class="flex justify-end pt-[15px] pr-[15px] items-center gap-[5px]"><strong>Date:</strong> <span class="text-[15px] text-[#000]">{{ date('d M Y')}}</span></div>
                    </td>
                </tr>
            </table>
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
                     <table class="w-[100%] 1border-[1px] 1border-[#f2f2f2]" >
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
                              @if(!empty($leadDetails->leadTasks ))
                                 @foreach ($leadDetails->leadTasks as $serK => $serV)    
                                                         
                                          @php
                                             $payment = $serV->payment->last();   
                                          @endphp
                                          
                                       <tr>
                                          <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$serV->services->serviceName}}</td>
                                          <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$serV->subService->subServiceName}}</td>
                                          <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$payment->service_price ?? 0}}</td>
                                          <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$payment->govt_price ?? 0}}</td>
                                          <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$payment->gst ?? 0}}</td>
                                          <td class="px-[16px] py-[16px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] 1border-r-[1px] 1border-r-[#f2f2f2]">{{$payment->total ?? 0}}</td>
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

                  <div class="">
                     <h2 class="text-[#000] text-[17px] font-[600] mb-[15px] text-[center] mb-[10px]">*Terms & Conditions:</h2>
                     <ul class="p-[0] pl-[20px] m-[0] mb-[25px]">
                        <li class="list-disc text-[15px] text-[#000] mb-[10px]">
                           Payment will be made by cash or Cheque or NEFT in favor "TRADE INNOVATION SERVICES PVT. LTD." and in 100% advance
                        </li>
                        <li class="list-disc text-[15px] text-[#000] mb-[10px]">
                           These charges do not include Show cause Hearing stage & Opposition stage fee.
                        </li>
                        <li class="list-disc text-[15px] text-[#000] mb-[10px]">
                           Application for filing will be prepared on the basis of this form. Regarding any wrong information in this form, Trade Innovation Services will not be responsible.
                        </li>
                        <li class="list-disc text-[15px] text-[#000] mb-[10px]">
                           No refund in any case.
                        </li>
                        <li class="list-disc text-[15px] text-[#000] mb-[10px]">
                           Any balance amount should be settled within 7 days from filing date of application. After 7 days, if
                        </li>
                     </ul>

                     <p class="text-[15px] text-[#ff0404] mb-[50px]">Amount dues remain, trade innovation services will not be responsible to your application for further proceedings.</p>
                  </div>
           </div>



           
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
      <button id="downloadPdf" class="download-btn px-[15px] py-[10px] bg-[#13103a] text-[15px] text-[#fff] rounded-[5px]">Download</button>
      <button class="px-[15px] py-[10px] bg-[#13103a] text-[15px] text-[#fff] rounded-[5px]">Send Email</button>
   </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
   // document.addEventListener("DOMContentLoaded", function () {
   //    document.querySelector(".download-btn").addEventListener("click", function () {
   //       const { jsPDF } = window.jspdf;
   //       let doc = new jsPDF("p", "mm", "a4");

   //       let element = document.querySelector("table");

   //       html2canvas(element, { scale: 2 }).then(canvas => {
   //          let imgData = canvas.toDataURL("image/png");

   //          // A4 page size in mm
   //          let imgWidth = 210; // A4 width in mm
   //          let imgHeight = (canvas.height * imgWidth) / canvas.width;

   //          // Ensure the image fits without extra space at the bottom
   //          if (imgHeight > 297) {  // A4 height in mm
   //             imgHeight = 297;
   //             imgWidth = (canvas.width * imgHeight) / canvas.height;
   //          }

   //          // Add the image at the top of the page without extra space
   //          doc.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight); // Position set to (0,0)

   //          doc.save("invoice.pdf");
   //       });
   //    });
   // });

   const url = "{{ route('lead.downloadinvoice', [$leadDetails->id]) }}";
   document.getElementById("downloadPdf").addEventListener("click", function () {
      fetch(url)
         .then(response => response.json())
         .then(data => {
               if (data.error) {
                  console.error(data.error);
                  return;
               }

               // Decode the Base64 string to binary data
               let pdfData = atob(data.pdf);

               // Convert the binary string to a byte array
               let byteArray = new Uint8Array(pdfData.length);
               for (let i = 0; i < pdfData.length; i++) {
                  byteArray[i] = pdfData.charCodeAt(i);
               }

               // Create a Blob from the byte array and specify the MIME type as 'application/pdf'
               let blob = new Blob([byteArray], { type: "application/pdf" });

               // Create a temporary download link
               let link = document.createElement("a");
               link.href = URL.createObjectURL(blob);
               link.download = "invoice.pdf"; // Specify the file name
               link.click(); // Trigger the download
         })
         .catch(error => console.error("PDF Download Error:", error));
   });


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