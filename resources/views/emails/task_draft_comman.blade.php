<div id="assignUserModal" class="hidden fixed inset-0 z-50 bg-[rgba(0,0,0,0.6)] flex justify-center items-center">
    <!-- Modal content: Only the table -->
    <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
       <tr>
          <td style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 10px 10px 0; text-align: center;">
             <img src="{{asset('assets/images/logo.png')}}" alt="Your Logo" style="max-width: 200px; margin-bottom: 10px;">
             {{-- <h1 style="color: #ffffff; font-size: 26px; margin: 0; font-weight: bold;">Welcome to Our Platform!</h1> --}}
          </td>
       </tr>
       <tr>
          <td style="padding: 15px 15px; text-align: left;">
             <p style="font-size: 16px; line-height: 1.8; margin: 0 0 10px; color: #555;">
               Hello <strong>{{ $clientName }}</strong> 
             </p>
             <p style="font-size: 16px; line-height: 1.8; margin: 0 0 20px; color: #555;">
                We have prepared the draft document for your review. Please take a moment to go through it, and if there are any corrections or adjustments needed, do let us know.
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
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{ $clientName }}</td>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{ $clientMobile}}</td>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{ $clientEmail }}</td>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2]">{{ $companyName }}</td>
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
                            <th class="px-[10px] py-[10px] text-[13px] font-[600] border-b-[1px] border-b-[#f2f2f2]  border-r-[1px] border-r-[#f2f2f2]">Current Service</th>
                            <th class="px-[10px] py-[10px] text-[13px] font-[600] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">Trademark Name</th>
                            <th class="px-[10px] py-[10px] text-[13px] font-[600] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]" >Application No.</th>
 
                         </tr>
                            <tr>
                               <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{ $service }}</td>
                               <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{ $trademarkName }}</td>
                               <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#f02929] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]" id="appNumber">{{ $assignApplicationNumber }}</td>
                              
                            </tr>
                      </table>
                   </td>
                </tr>
             </table>
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
 </div>