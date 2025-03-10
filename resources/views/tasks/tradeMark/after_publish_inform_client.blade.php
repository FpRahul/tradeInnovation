@extends('layouts.default')
@section('content')
<style>
   .Hidden {
      display: none;
   }
</style>
<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
   <x-client-task-details :taskID="$id" />
</div>
<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white px-[15px] md:px-[30px] py-[20px] rounded-[20px] mt-[20px] overflow-hidden ">

   <form action="{{route('task.informClientAfterPublishStatus',['id'=>$id]) }}" method="POST" class="space-y-[20px]" enctype="multipart/form-data">
      @csrf
      <strong class="mt-4 block"> Update Current Task</strong>
      <div class="flex flex-col md:flex-row gap-[20px]">
         <input type="hidden" name="checkValid" id="checkValid" value="">
         <div class="w-full md:w-1/2" id="verifiedDate">
            <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
               Informed on
            </label>
            <div class="w-[100%] relative">
               <input
                  type="text"
                  placeholder="Dead Line"
                  name="verified"
                  class="daterangepicker-verified w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
                  value=""
                  id="verified"
                  autocomplete="off">
               <div class="absolute right-[10px] top-[10px]">
                  <i class="ri-calendar-line"></i>
               </div>
            </div>
         </div>
         @if(!empty($clientName))
         <input type="hidden" id="clientName" value="{{$clientName}}">
         @endif


         <div class="flex justify-start flex-wrap w-[100%] md:w-[49%]">
            <label class="block w-full text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload</label>
            <label for="attachment" class="flex items-center gap-[10px] w-full text-[13px] font-[500] leading-[15px] text-[#666666] tracking-[0.01em] bg-[#fff] border-dashed border-[1px] border-[#ccc] rounded-[6px] py-[6px] px-[10px] cursor-pointer">
               <svg width="22" height="28" viewBox="0 0 22 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M10.248 15.6125V21.9035C10.248 22.1175 10.3195 22.296 10.4625 22.439C10.6055 22.582 10.784 22.6535 10.998 22.6535C11.212 22.6535 11.3905 22.582 11.5335 22.439C11.6765 22.296 11.748 22.1175 11.748 21.9035V15.6125L14.367 18.2315C14.44 18.3045 14.522 18.3595 14.613 18.3965C14.705 18.4325 14.7965 18.4495 14.8875 18.4475C14.9785 18.4445 15.0735 18.4245 15.1725 18.3875C15.2705 18.3515 15.3555 18.2965 15.4275 18.2225C15.5875 18.0575 15.669 17.881 15.672 17.693C15.675 17.504 15.594 17.327 15.429 17.162L11.8455 13.5785C11.7155 13.4485 11.582 13.357 11.445 13.304C11.309 13.251 11.16 13.2245 10.998 13.2245C10.836 13.2245 10.687 13.251 10.551 13.304C10.415 13.357 10.2815 13.4485 10.1505 13.5785L6.56705 17.162C6.42105 17.308 6.34455 17.48 6.33755 17.678C6.33055 17.876 6.41005 18.058 6.57605 18.224C6.74105 18.383 6.91805 18.464 7.10705 18.467C7.29605 18.47 7.47255 18.389 7.63655 18.224L10.248 15.6125ZM2.92205 27.5C2.23105 27.5 1.65455 27.269 1.19255 26.807C0.730547 26.345 0.499047 25.7685 0.498047 25.0775V2.9225C0.498047 2.2325 0.729547 1.6565 1.19255 1.1945C1.65555 0.7325 2.23205 0.501 2.92205 0.5H13.7415C14.0645 0.5 14.3785 0.565 14.6835 0.695C14.9885 0.825 15.2495 0.9995 15.4665 1.2185L20.778 6.53C20.995 6.748 21.169 7.009 21.3 7.313C21.431 7.617 21.4965 7.931 21.4965 8.255V25.076C21.4965 25.766 21.265 26.3425 20.802 26.8055C20.339 27.2685 19.7635 27.5 19.0755 27.5H2.92205ZM13.998 6.788C13.998 7.137 14.113 7.426 14.343 7.655C14.573 7.884 14.862 7.999 15.21 8H19.998L13.998 2V6.788Z" fill="#13103A" />
               </svg>
               Upload File
            </label>
            <input type="file" id="attachment" name="attachment[]" multiple style="display: none;" />
            <div id="file-list" class="mt-2"></div>
         </div>
         @error('attachment.*')
         <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
         @enderror
      </div>
      <div class="">
         <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
         <textarea type="text" name="description" id="description" class="w-full h-[60px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>

      </div>

      <strong class="mt-5 block">Update Upcoming Actions</strong>
      <div class="flex flex-col md:flex-row gap-[20px]">
         <div class="w-full md:w-1/2">
            <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Satge</label>
            @if($getStage->count() > 0)
            <input type="text" name="stage_id" id="stage_id" value="{{$getStage->title}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" disabled>
            <input type="hidden" name="stage_id" value="{{$getStage->id}}">
            @endif
            <p style="color: skyblue; font-size: 14px; font-weight: 500;">
               Next stage will be: {{$getStage->title}}
            </p>
         </div>
         @if($taskDetails->count() > 0)
         @foreach ($taskDetails as $user )
         @php
         $selectedId = $user->user->id;
         @endphp
         @endforeach
         @endif
         <div class="w-full md:w-1/2">

            <label for="assignUser" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign User</label>
            <select name="assignUser" id="assignUser" class="filterData assignUserData allform-select2 !outline-none h-[45px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A]">
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
            @foreach ($taskDetails as $user )
            <p style="color: skyblue; font-size: 14px; font-weight: 500;">
               Current user assigned: {{$user->user->name}}.
            </p>
            @endforeach
            @endif
         </div>
      </div>
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
         @if($getStage->count() > 0)
         <p style="color: skyblue; font-size: 14px; font-weight: 500;">
            Set a dead line for:{{ $getStage->title }}.
         </p>
         @endif
      </div>
      <strong class="mt-4 block">Payment Details</strong>
      <div class="flex flex-col md:flex-row gap-[20px]">
         <div class="w-full md:w-1/2">
            <label for="subject" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Subject<strong class="text-[#f83434]">*</strong></label>
            <input type="text" name="subject" id="subject" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
            @error('subject')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
         </div>
         <div class="w-full md:w-1/2">
            <label for="Service" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Service<strong class="text-[#f83434]">*</strong></label>
            <input type="text" id="serviceNameInput" value="{{$serviceName}}" disabled class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
            <input type="hidden" name="service" value="{{$serviceName}}">
            @error('service')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
         </div>
      </div>
      <div class="flex flex-col md:flex-row gap-[20px]">
         <div class="w-full md:w-1/2">
            <label for="subject" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Price<strong class="text-[#f83434]">*</strong></label>
            <input type="text" name="service_price" id="service_price" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
            @error('service_price')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
         </div>
         <div class="w-full md:w-1/2">
            <label for="subject" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Govt Price<strong class="text-[#f83434]">*</strong> </label>
            <input type="text" name="govt_price" id="govt_price" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
            @error('govt_price')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
         </div>
      </div>
      <div class="w-full md:w-1/2 flex items-center gap-[10px]">
         <input type="checkbox" name="gst" id="gst" value="1" class="w-auto h-[20px] !outline-none">
         <label for="govt_price" class="text-[14px] font-[400] leading-[16px] text-[#000000]">GST</label>
      </div>
      <div class="flex justify-end gap-[15px]">
         <a class="preview text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Preview</a>
         <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Sent Quotation</button>
      </div>
   </form>
</div>
<div id="assignUserModal" class="hidden fixed inset-0 z-50 bg-[rgba(0,0,0,0.6)] flex justify-center items-center">
   <!-- Modal content: Only the table -->
   <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
      <tr>
         <td style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 60px 30px; text-align: center;">
            <img src="https://futureprofilez.com/wp-content/themes/fptheme/assets2/img/logo.png?dshf" alt="Your Logo" style="max-width: 200px; margin-bottom: 10px;">
            <h1 style="color: #ffffff; font-size: 26px; margin: 0; font-weight: bold;">Welcome to Our Platform!</h1>
         </td>
      </tr>
      <tr>
         <td style="padding: 40px 20px; text-align: left;">
            <p style="font-size: 16px; line-height: 1.8; margin: 0 0 10px; color: #555;">
               Hello <strong id="mailClientName"></strong>,
            </p>
            <p style="font-size: 16px; line-height: 1.8; margin: 0 0 20px; color: #555;">
               Your service request for <strong></strong> has been processed. Below are the details of your service and the associated pricing:
            </p>
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
</div>

<script>
   $(document).ready(function() {
      $('.daterangepicker-verified').daterangepicker({
         singleDatePicker: true,
         opens: 'right',
         locale: {
            format: 'DD MMM YYYY'
         },
         minDate: null,
         maxDate: moment().endOf('day'),
      }).on('apply.daterangepicker', function(ev, picker) {
         console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
      });

      $('.daterangepicker-taskdeadline').daterangepicker({
         singleDatePicker: true,
         opens: 'right',
         locale: {
            format: 'DD MMM YYYY'
         },
         minDate: moment().startOf('day'),

      }).on('apply.daterangepicker', function(ev, picker) {
         console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
      });

      $("#status").on("change", function() {
         var changedValue = $(this).val();
         if (changedValue == 1) {
            $('label[for="verified"]').text('Sent On');
            $("#verifiedDate").removeClass('Hidden');
         } else {
            $('label[for="verified"]').text('Verified On');
            $("#verifiedDate").addClass('Hidden');
         }
      });

      var name = $("#serviceNameInput").val()
      $("#mailService").text(name)
      var clientName = $("#clientName").val()
      $("#mailClientName").text(clientName)

      $('#service_price').on('input', function() {
         let servicePrice = parseFloat($(this).val());
         $('#mailServicePrice').text('₹' + servicePrice);
          
         updateGst();
      });

      $('#govt_price').on('input', function() {
         let govtPrice = parseFloat($(this).val());
         $('#mailGovtPrice').text("₹" + govtPrice);
         

         updateGst();
      });

      $('#gst').on('change', function() {
         updateGst();
      });

      function updateGst() {
         let servicePrice = parseFloat($('#service_price').val());
         let govtPrice = parseFloat($('#govt_price').val());

         if (isNaN(servicePrice) || isNaN(govtPrice)) {
            return;
         }

         let totalPrice = servicePrice + govtPrice;
         let gstPrice = servicePrice * 0.18;
         let overAllTotalWithGst = totalPrice +  gstPrice;
         let overAllTotal = totalPrice;

         if ($('#gst').prop('checked')) {
            $('#mailGst').text("₹" + gstPrice.toFixed(2) + "(18%)");
            $("#mailTotal").text("₹" + overAllTotalWithGst.toFixed(2) )
         } else {
            $('#mailGst').text("Not apply");
            $("#mailTotal").text("₹" + overAllTotal.toFixed(2) )
         }
      }


      $('.preview').on('click', function() {
         $('#assignUserModal').removeClass('hidden');
      });


      $('#assignUserModal').on('click', function(e) {
         if ($(e.target).is('#assignUserModal')) {

            $('#assignUserModal').addClass('hidden');
         }
      });
   });
</script>
@stop