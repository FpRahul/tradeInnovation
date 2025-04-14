@extends('layouts.default')
@section('content')
<style>
   .Hidden {
      display: none;
   }
</style>
<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
   <x-client-task-details :taskID="$taskId" />
</div>
<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white px-[15px] md:px-[30px] py-[20px] rounded-[20px] mt-[20px] overflow-hidden ">
   <form action="{{route('task.patentSERSubmit',['id'=>$taskId]) }}" method="POST" class="space-y-[20px]" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="previous_task_id" id="previous_task_id" value="{{$previousTask->id}}">
      <strong class="mt-4 block"> Update Current Task</strong>
     
</div>
<div class="">
   <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
   <textarea type="text" name="description" id="description" class="w-full h-[80px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>
   @error('description')
   <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
   @enderror
</div>
<div class="upcomingStageHideShow">
   <strong class="mt-5 block">Update Upcoming Actions</strong>
   <div class="flex flex-col md:flex-row gap-[20px]">     

      <div class="w-full md:w-1/2">
         <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Stage</label>
         @if($getStage)
               <input type="text" name="stage_id" id="stage_id" value="{{ $getStage->title }}" class="stage_text w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" disabled>
               <input type="hidden" name="stage_id" class="stage_id" value="{{ $getStage->id }}">
         @endif  
         <p style="color: skyblue; font-size: 14px; font-weight: 500;">
               Next stage will be: <span class="stage_title">{{$getStage->title ?? ''}}</span>
         </p> 
      </div>
      @if($taskDetails->count() > 0)
      @php
      $selectedId = $taskDetails->user->id;
      @endphp
      @endif
      <div class="w-full md:w-1/2">
         <label for="assignUser" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign User</label>
         <select name="assignUser" id="assignUser" class="filterData assignUserData allform-select2 !outline-none h-[45px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A]" required>
            <option value="" disabled selected>Select a user</option>
            @if($users->count() > 0)
            <option value="" disabled selected>Select a user</option>
            @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ !empty($selectedId) && $user->id == $selectedId ? 'selected' : '' }}>
               {{ $user->name }}
            </option>
            @endforeach
            @else
            <option value="" disabled>No users available</option>
            @endif
         </select>
         @if($taskDetails->count() > 0)
         <p style="color: skyblue; font-size: 14px; font-weight: 500;">
            Current user assigned: {{$taskDetails->user->name}}.
         </p>
         @endif
      </div>
   </div>

   <div class="  flex flex-col md:flex-row gap-[20px]">

      <div class="w-full md:w-1/2" id="verifiedDate">
         <label for="deadline" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
            Dead line
         </label>
         <div class="w-[100%] relative">
            <input
               type="text"
               placeholder="Dead Line"
               name="deadline"
               id="deadline"
               class="daterangepicker-taskdeadline w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
               value=""

               autocomplete="off">
            <div class="absolute right-[10px] top-[10px]">
               <i class="ri-calendar-line"></i>
            </div>
         </div>
         <p style="color: skyblue; font-size: 14px; font-weight: 500;">
            @if($getStage)
            Set a dead line for: <span class="stage_title">{{$getStage->title}}</span>
            @endif
         </p>
      </div>
   </div>
</div>
   


   <div class="flex justify-end gap-[15px]">
    <a class="preview text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Preview</a>

      <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
   </div>
</form>
</div>
<div id="assignUserModal" class="hidden fixed inset-0 z-50 bg-[rgba(0,0,0,0.6)] flex justify-center items-center">
    <!-- Modal content: Only the table -->
    <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
       <tr>
          <td style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 60px 30px 0; text-align: center;">
             <img src="{{asset('assets/images/logo.png')}}" alt="Your Logo" style="max-width: 200px; margin-bottom: 10px;">
             <h1 style="color: #000; font-size: 20px; margin: 0; font-weight: bold;">Welcome to Our Platform!</h1>
          </td>
       </tr>
       <tr>
          <td style="padding: 15px 15px; text-align: left;">
             <p style="font-size: 16px; line-height: 1.8; margin: 0 0 10px; color: #555;">
                Hello <strong id="mailClientName"></strong>,
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
                            @if(!empty($taskDetails))
                            @foreach ($taskDetails as $userK => $userV)
                               
                               <tr>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$userV->lead->client_name}}</td>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$userV->lead->mobile_number}}</td>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">{{$userV->lead->email}}</td>
                                  <td class="px-[10px] py-[10px] text-[12px] font-[400] text-[#000] border-b-[1px] border-b-[#f2f2f2]">{{ $userV->lead->company_name  }}</td>
                               </tr>
                            @endforeach
                            @endif
                            
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
                            <th class="px-[10px] py-[10px] text-[13px] font-[600] border-b-[1px] border-b-[#f2f2f2] border-r-[1px] border-r-[#f2f2f2]">Current Sub-Service</th>
                           
                            
                         </tr>
                         @if(!empty($taskDetails))
                            @foreach ($taskDetails as $serK => $serV)                              
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
             <div id="popup-attachment" class="hidden">
                <h4>Attachments</h4>
                <div id="modal-file-preview" class="flex gap-4 flex-wrap p-4"></div>
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
<script>
   

    $(document).ready(function() {
      $('.daterangepicker-verified').attr("placeholder", "DD/MM/YYYY"); // Set placeholder

        $('.daterangepicker-verified').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            opens: 'right',
            locale: {
                format: 'DD MMM YYYY'
            },
            minDate: null,
            maxDate: moment().endOf('day'),
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY')); 
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
        });

        $('.daterangepicker-taskdeadline').attr("placeholder", "DD/MM/YYYY"); 

         $('.daterangepicker-taskdeadline').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false, 
            opens: 'right',
            locale: {
               format: 'DD MMM YYYY'
            },
            minDate: moment().startOf('day'),
         }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY')); 
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
         });
   });
</script>
@stop