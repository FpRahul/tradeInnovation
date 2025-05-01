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
   <form action="{{route('task.patentInCertiFiledSubmit',['id'=>$id]) }}" method="POST" class="space-y-[20px]" enctype="multipart/form-data">
      @csrf
      <strong class="mt-4 block"> Update Current Task</strong>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <input type="hidden" name="inventor_id" value="{{$taskDetails->serviceDetails->id ?? 0}}"/>
            <div class="w-full md:w-1/2 ">
                <label for="application_number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Application Number<strong class="text-[#f83434]"></strong></label>
                <input type="text" name="application_number" required placeholder="Application Number" id="application_number" value="{{$taskDetails->serviceDetails->application_number ?? ''}}" required class="application_number w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                @error('application_number')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full md:w-1/2 ">
                <label for="patent_title" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Patent Title<strong class="text-[#f83434]"></strong></label>
                <input type="text" name="patent_title" required placeholder="Patent Title" id="patent_title" value="{{$taskDetails->serviceDetails->applied_for ?? ''}}" required class="patent_title w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                @error('patent_title')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2 ">
                <label for="inventor_name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Inventor Name<strong class="text-[#f83434]"></strong></label>
                <input type="text" name="inventor_name" required placeholder="Inventor Name" id="inventor_name" value="{{$taskDetails->serviceDetails->inventor_name ?? ''}}"  class="inventor_name w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                @error('inventor_name')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
           
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2" id="verifiedDate">
                <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                Patent Grant
                </label>
                <div class="w-[100%] relative">
                <input
                    type="text"
                    placeholder="Patent Grant"
                    name="patent_grant"
                    class="patent_grant_date daterangepicker-verified w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
                    value="{{ isset($taskDetails->serviceDetails->grant_date) ? \Carbon\Carbon::parse($taskDetails->serviceDetails->grant_date)->format('d M Y') : '' }}"

                    id="patent_grant"
                    autocomplete="off" required>
                <div class="absolute right-[10px] top-[10px]">
                    <i class="ri-calendar-line"></i>
                </div>
                </div>
            </div>
            <div class="w-full md:w-1/2" id="verifiedDate">
                <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                Certificate Issued Date
                </label>
                <div class="w-[100%] relative">
                <input
                    type="text"
                    placeholder="Certificate Issued Date"
                    name="certificate_issued"
                    class="certificate_issued_date daterangepicker-verified w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
                    value="{{ isset($taskDetails->serviceDetails->certificate_issued_date) ? \Carbon\Carbon::parse($taskDetails->serviceDetails->certificate_issued_date)->format('d M Y') : '' }}"
                    id="certificate_issued"
                    autocomplete="off" required>
                <div class="absolute right-[10px] top-[10px]">
                    <i class="ri-calendar-line"></i>
                </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2 ">
                <label for="applicant_assignee_name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Applicant/Assignee Name<strong class="text-[#f83434]"></strong></label>
                <input type="text" name="applicant_assignee_name" required placeholder="Applicant/Assignee Name" id="applicant_assignee_name" value="{{$taskDetails->serviceDetails->applicant_name ?? ''}}"  class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                @error('applicant_assignee_name')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full md:w-1/2" id="verifiedDate">
                <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                filed On
                </label>
                <div class="w-[100%] relative">
                <input
                    type="text"
                    placeholder="Dead Line"
                    name="verified"
                    class="daterangepicker-verified w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
                    value=""
                    id="verified"
                    autocomplete="off" required>
                <div class="absolute right-[10px] top-[10px]">
                    <i class="ri-calendar-line"></i>
                </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2 ">
                <label for="mail_subject" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mail Subject<strong class="text-[#f83434]"></strong></label>
                <input type="text" name="mail_subject" required placeholder="Enter mail subject" id="mail_subject" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                @error('mail_subject')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
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
      <div class="">
         <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
         <textarea type="text" name="description" id="description" class="w-full h-[80px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>
         @error('description')
         <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
         @enderror
      </div>

      <strong class="mt-5 block">Update Upcoming Actions</strong>
      <div class="flex flex-col md:flex-row gap-[20px]">
         <div class="w-full md:w-1/2">
            <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Stage</label>
            @if($getStage->count() > 0)
            <input type="text" name="stage_id" id="stage_id" value="{{$getStage->title}}" class="  w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" disabled>
            <input type="hidden" name="stage_id" value="{{$getStage->id}}">
            @endif
             <p style="color: skyblue; font-size: 14px; font-weight: 500;">
               Next stage will be: {{$getStage->title}}
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
               Deadline
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
               Set a dead line for: {{$getStage->title}}
            </p>
            @endif
         </div>
      </div>

      <div class="flex justify-end gap-[15px]">
         <a  id= "previewMail" class=" preview text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Preview</a>

         <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
      </div>
   </form>
</div>
<div id="assignUserModal" class="hidden fixed inset-0 z-50 bg-[rgba(0,0,0,0.6)] flex justify-center items-center">
   <div class="bg-white rounded-xl shadow-xl w-[750px] max-w-full p-8">
      <!-- Header -->
      <div class="text-center border-b pb-4 mb-6">
         <img src="{{ asset('assets/images/logo.png') }}" alt="Your Logo" class="mx-auto max-w-[200px] mb-3">
         <h1 class="text-2xl font-bold text-[#1c194d]">Certificate of Patent Renewal Filing</h1>
         <p class="text-sm text-gray-600 italic">We proudly certify the successful filing and renewal of the following patent</p>
      </div>

      <!-- Main Description -->
      <div class="mb-6">
         <p class="text-[16px] text-gray-700 leading-7">
            This certificate is awarded to <strong>{{ $taskDetails->lead->client_name }}</strong> from 
            <strong>{{ $taskDetails->lead->company_name }}</strong> for the successful renewal of the patent mentioned below.
         </p>
      </div>

      <!-- Patent Information Table -->
      <div class="mb-8">
         <h2 class="text-[17px] font-semibold text-[#1c194d] mb-4">Patent Details</h2>
         <table class="w-full border border-gray-300 text-sm text-left">
            <tbody>
               <tr class="bg-gray-100">
                  <th class="border border-gray-300 px-4 py-2">Patent Title</th>
                  <td class="border border-gray-300 px-4 py-2 certificate_patent_title">{{ $taskDetails->serviceDetails->applied_for }}</td>
               </tr>
               <tr>
                  <th class="border border-gray-300 px-4 py-2">Application Number</th>
                  <td class="border border-gray-300 px-4 py-2 text-[#f02929] font-semibold" id="appNumber">{{ $taskDetails->serviceDetails->application_number }}</td>
               </tr>
               <tr class="bg-gray-100">
                  <th class="border border-gray-300 px-4 py-2">Patent Grant Date</th>
                  <td class="border border-gray-300 px-4 py-2 patent_grant_certificate_date">{{ $taskDetails->serviceDetails->grant_date ?? 'N/A' }}</td>
               </tr>
               <tr>
                  <th class="border border-gray-300 px-4 py-2">Current Service</th>
                  <td class="border border-gray-300 px-4 py-2">{{ $taskDetails->services->serviceName }}</td>
               </tr>
               <tr class="bg-gray-100">
                  <th class="border border-gray-300 px-4 py-2">Inventor Name</th>
                  <td class="border border-gray-300 px-4 py-2 certificate_inventor_name">{{ $taskDetails->serviceDetails->inventor_name ?? $taskDetails->lead->client_name }}</td>
               </tr>
            </tbody>
         </table>
      </div>

      <!-- Client Information Table -->
      <div class="mb-8">
         <h2 class="text-[17px] font-semibold text-[#1c194d] mb-4">Client Details</h2>
         <table class="w-full border border-gray-300 text-sm text-left">
            <thead>
               <tr class="bg-gray-100">
                  <th class="border border-gray-300 px-4 py-2">Name</th>
                  <th class="border border-gray-300 px-4 py-2">Mobile</th>
                  <th class="border border-gray-300 px-4 py-2">Email</th>
                  <th class="border border-gray-300 px-4 py-2">Company</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td class="border border-gray-300 px-4 py-2">{{ $taskDetails->lead->client_name }}</td>
                  <td class="border border-gray-300 px-4 py-2">{{ $taskDetails->lead->mobile_number }}</td>
                  <td class="border border-gray-300 px-4 py-2">{{ $taskDetails->lead->email }}</td>
                  <td class="border border-gray-300 px-4 py-2">{{ $taskDetails->lead->company_name }}</td>
               </tr>
            </tbody>
         </table>
      </div>

      <!-- Attachments Section -->
      <div class="mb-8">
         <h3 class="text-[16px] font-semibold mb-2">Attachments</h3>
         <div id="modal-file-preview" class="flex gap-3 flex-wrap p-2 border border-gray-200 rounded-lg bg-gray-50"></div>
      </div>

      <!-- Certificate Footer -->
      <div class="text-center text-gray-700 text-[16px] leading-6 mb-8">
         <p>We thank you for your association and trust in our services.</p>
         <p>For further assistance, contact our support team anytime.</p>
      </div>

      <!-- Date & Authority -->
      <div class="text-right text-sm text-gray-600">
         <p>Patent Certificate Issued Date: <strong class="patent_certificate_issued_date">{{ \Carbon\Carbon::now()->format('d M, Y') }}</strong></p>
         <p class="mt-1">Authorized By: <strong>Your Company Name</strong></p>
      </div>

      <!-- Footer -->
      <div class="mt-6 text-center text-white bg-[#1c194d] py-3 rounded-lg text-sm">
         © 2025 Your Company Name — All Rights Reserved
      </div>
   </div>
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

      $(".preview").off("click").on("click", function () {
         $("#assignUserModal").removeClass("hidden");
      });

      $('#assignUserModal').on('click', function(e) {
         if ($(e.target).is('#assignUserModal')) {
            $('#assignUserModal').addClass('hidden');
         }
      });

      $("#application_number").on("input", function () {
         let inputVal = $(this).val();
         $("#appNumber").text(inputVal ? inputVal : "Not Available");
      });  

      $('.patent_grant_date').on('apply.daterangepicker',function(){
         let patentGrantDate = $(this).val();
         $('.patent_grant_certificate_date').text(patentGrantDate);
      });

      $('.certificate_issued_date').on('apply.daterangepicker',function(){
         let certificate_issued_date = $(this).val();
         $('.patent_certificate_issued_date').text(certificate_issued_date);
      });

      $('#assignUserModal').on('click', function(e) {
         if ($(e.target).is('#assignUserModal')) {

            $('#assignUserModal').addClass('hidden');
         }
      });

      $("#attachment").on("change", function () {
         let files = this.files;
         let fileList = $("#file-list");
         let previewList = $("#modal-file-preview"); // Ensure this div exists inside your modal

         fileList.empty();
         previewList.empty();

         if (files.length > 0) {
            $.each(files, function (index, file) {
                  let fileType = file.type;
                  let reader = new FileReader();

                  // Append file name to the form file list
                  fileList.append(`<p class="text-[#000] text-sm">${file.name}</p>`);

                  // Create a preview in the modal
                  reader.onload = function (e) {
                     let previewElement = "";
                     
                     if (fileType.startsWith("image")) {
                        previewElement = `<img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg border" alt="Preview">`;
                     } else {
                        previewElement = `<p class="text-sm text-gray-700">${file.name}</p>`;
                     }

                     previewList.append(previewElement);
                  };

                  reader.readAsDataURL(file);
            });

            // Show modal when files are selected
            
         }
      });

      $(".inventor_name").on("input", function () {
         let inputVal = $(this).val();
         $(".certificate_inventor_name").text(inputVal ? inputVal : "Not Available");
      });  

      $(".patent_title").on("input", function () {
         let inputVal = $(this).val();
         $(".certificate_patent_title").text(inputVal ? inputVal : "Not Available");
      });
   });
</script>
@stop