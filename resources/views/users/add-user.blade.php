@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}} Employee</h3>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('users.adduser', ['id' => $newUser->id]) }}" enctype="multipart/form-data" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
            @csrf

            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="name" id="name" value="{{ $newUser->name }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Name" required>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="w-full md:w-1/2">
                    <label for="role" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Role <strong class="text-[#f83434]">*</strong></label>
                    <select name="role" id="role" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Select Role</option>
                        @if (count($roleData) > 0)
                            @foreach ($roleData as $roles)
                                <option value="{{$roles->id}}" {{$newUser->role == $roles->id ? 'selected':''}}>{{$roles->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('role')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="fatherHusbandName" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Father/Husband Name <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="fatherHusbandName" id="fatherHusbandName" value="{{ !empty($newUserDetails->fatherHusbandName) ? $newUserDetails->fatherHusbandName : '' }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Company Name" required>
                    @error('fatherHusbandName')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="employeePhoto" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Photograph: <span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg and png format supported & max:2 MB)</span></label>
                    <div class="relative flex flex-wrap gap-[10px]">
                        <img class="getpreviewImage" src="{{asset(!empty($newUserDetails->uploadPhotograph) ? 'Image/'.$newUserDetails->uploadPhotograph : 'assets/images/noimage.png')}}" width="70" height="70" class="w-[83px] h-[45px] rounded-[10px] object-cover shadow-[0_0_5px_rgba(0,0,0,0.3)]" />
                        <div class="relative">
                            <label for="employeePhoto" class="cursor-pointer w-[83px] h-[45px] rounded-[10px] flex items-center justify-center border border-dashed border-[#13103a4d] ">
                                <svg class="cursor-pointer" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.9395 8.95044H8.93945V13.9504C8.93945 14.5004 8.48945 14.9504 7.93945 14.9504C7.38945 14.9504 6.93945 14.5004 6.93945 13.9504V8.95044H1.93945C1.38945 8.95044 0.939453 8.50044 0.939453 7.95044C0.939453 7.40044 1.38945 6.95044 1.93945 6.95044H6.93945V1.95044C6.93945 1.40044 7.38945 0.950439 7.93945 0.950439C8.48945 0.950439 8.93945 1.40044 8.93945 1.95044V6.95044H13.9395C14.4895 6.95044 14.9395 7.40044 14.9395 7.95044C14.9395 8.50044 14.4895 8.95044 13.9395 8.95044Z" fill="#13103A" />
                                </svg>
                            </label>
                            <input type="file" name="employeePhoto" id="employeePhoto" class="previewImage w-0 opacity-0 absolute top-0 left-0">
                        </div>
                    </div>
                    @error('employeePhoto')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">

                <div class="w-full md:w-1/2">
                    <label for="mobileNumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile Number <strong class="text-[#f83434]">*</strong></label>
                    <input type="number" name="mobileNumber" id="mobileNumber" value="{{ $newUser->mobile }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Mobile Number" required>
                    @error('mobileNumber')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="altMobile" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Mobile Number</label>
                    <input type="number" name="altMobile" id="altMobile" value="{{ $newUser->altNumber }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Alternate Mobile Number" >
                    @error('altMobile')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">

                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="email" id="email" value="{{ $newUser->email }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email" required>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="qualification" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Qualification <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="qualification" id="qualification" value="{{ !empty($newUserDetails->qualification) ? $newUserDetails->qualification :'' }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Qualification" required>
                    @error('qualification')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div>
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Experience</label>
            <div class="userExperienceRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                <div class="repeater-default">
                    <div data-repeater-list="experince" class="flex flex-col gap-[20px]">
                        @if (count($newUserExperiences)>0)
                            @foreach ($newUserExperiences as $exKey => $exVal)
                                <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                                    <div class="w-[calc(100%-75px)] grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-[20px]">
                                        <input type="hidden" name="experience_id" class="experience_id" value="{{ $exVal['id']}}">
                                        <div class="w-full">
                                            <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Employer
                                            </label>
                                            <input type="text" name="employerName" id="employerName" value="{{ $exVal['employerName']}}"
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                                                placeholder="Employer Name">
                                        </div>
<<<<<<< HEAD
                                        <div class="w-full">
                                            <label for="startDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Start Date
                                            </label>
                                            {{-- <input type="date" name="startDate" id="startDate" value="{{ $exVal['startDate']}}"
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"> --}}
                                            <div class="w-[100%] relative">
                                                <input 
                                                    type="text" 
                                                    placeholder="Start Date" 
                                                    name="startDate" 
                                                    class="daterangepicker-startDate w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                                                    value="{{ $exVal['startDate'] }}" 
                                                    autocomplete="off"
                                                >
                                                <div class="absolute right-[10px] top-[10px]">
                                                <i class="ri-calendar-line"></i>
                                                </div>
                                            </div>                                                
=======

                                        

                                        <div class="w-[16%] relative">
                                        <label for="startDate" class="flex text-[15px] text-[#000] mb-[5px]">
                                            Start Date
                                        </label>
                                        <div class="w-[100%] relative">
                                            <input type="text" name="startDate" id="startDate" placeholder="YYYY-MM-DD"
                                                class="daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                                            <i class="ri-calendar-line absolute right-[8px] top-[9px]" id="calendarIcon"></i>
>>>>>>> 4566d0e83d837eb14ee26359bf874df268a4ef07
                                        </div>
                                    </div>

                                    <div class="w-[16%] relative">
                                            <label for="endDate" cclass="flex text-[15px] text-[#000] mb-[5px]">
                                                End Date
                                            </label>
                                            {{-- <input type="date" name="endDate" id="endDate" value="{{ $exVal['endDate']}}"
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"> --}}
                                                <div class="w-[100%] relative">
                                                    <input type="text" placeholder="Start Date" name="endDate" class="daterangepicker-endDate w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" value="{{ $exVal['endDate'] }}">
                                                    <div class="absolute right-[10px] top-[10px]">
                                                        <i class="ri-calendar-line"></i>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="w-[55px] ">
                                        <span data-repeater-delete data-id="{{ $exVal['id']}}" class="deleteRepeaterRow flex items-center justify-center w-full h-[45px] border-[1px] border-[#0000001A] rounded-[10px] text-center">
                                            <span class="glyphicon glyphicon-remove"></span>
                                            <svg class="mx-auto" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.616 16C2.15533 16 1.771 15.846 1.463 15.538C1.155 15.23 1.00067 14.8453 1 14.384V2H0V1H4V0.230003H10V1H14V2H13V14.385C13 14.845 12.846 15.2293 12.538 15.538C12.23 15.8467 11.8453 16.0007 11.384 16H2.616ZM4.808 13H5.808V4H4.808V13ZM8.192 13H9.192V4H8.192V13Z" fill="#FF0000" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                        <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                            <div class="w-[calc(100%-75px)] grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-[20px]">
                                <input type="hidden" name="experience_id" class="experience_id" value="0">
                                <div class="w-full">
                                    <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                        Employer
                                    </label>
                                    <input type="text" name="employerName" id="employerName"
                                        class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                                        placeholder="Employer Name">
                                </div>
                                <div class="w-full">
                                    <label for="startDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                        Start Date
                                    </label>
                                    {{-- <input type="date" name="startDate" id="startDate" value=""
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"> --}}
                                    <div class="w-[100%] relative">
                                        <input 
                                            type="text" 
                                            placeholder="Start Date" 
                                            name="startDate" 
                                            class="daterangepicker-startDate w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                                            value="" 
                                            autocomplete="off"
                                        >
                                        <div class="absolute right-[10px] top-[10px]">
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                        End Date
                                    </label>
                                    {{-- <input type="date" name="endDate" id="endDate" value=""
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"> --}}
                                    <div class="w-[100%] relative">
                                        <input type="text" placeholder="Start Date" name="endDate" class="daterangepicker-endDate w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" value="">
                                        <div class="absolute right-[10px] top-[10px]">
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-[55px]">
                                <span data-repeater-delete data-id="0" class="deleteRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    <svg class="mx-auto" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.616 16C2.15533 16 1.771 15.846 1.463 15.538C1.155 15.23 1.00067 14.8453 1 14.384V2H0V1H4V0.230003H10V1H14V2H13V14.385C13 14.845 12.846 15.2293 12.538 15.538C12.23 15.8467 11.8453 16.0007 11.384 16H2.616ZM4.808 13H5.808V4H4.808V13ZM8.192 13H9.192V4H8.192V13Z" fill="#FF0000" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="mt-[20px]">
                        <a href="javascript:void(0)" data-repeater-create class="inline-block text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]"> Add More </a>
                    </div>
                </div>
            </div>
            </div>

            <div class="flex flex-col md:flex-row gap-[20px]">

                <div class="w-full md:w-1/2">
                    <label for="skill" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Skills <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="skill" id="skill" value="{{ !empty($newUserDetails->skills) ? $newUserDetails->skills :'' }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255"  required>
                    @error('skill')
                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="keyResponsibilityArea" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Key Responsibility Area <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="keyResponsibilityArea" id="keyResponsibilityArea" value="{{ !empty($newUserDetails->keyResponsibilityArea) ? $newUserDetails->keyResponsibilityArea :'' }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" required>
                    @error('keyResponsibilityArea')
                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">

                <div class="w-full md:w-1/2">
                    <label for="keyPerformanceIndicator" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Key Performance Indicator</label>
                    <input type="text" name="keyPerformanceIndicator" id="keyPerformanceIndicator" value="{{ !empty($newUserDetails->keyPerformanceIndicator) ? $newUserDetails->keyPerformanceIndicator :'' }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255">
                    @error('keyPerformanceIndicator')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="emergencyContactDetails" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Emergency Contact Details</label>
                    <input type="text" name="emergencyContactDetails" id="emergencyContactDetails" value="{{ !empty($newUserDetails->emergencyContactDetails) ? $newUserDetails->emergencyContactDetails :'' }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                    @error('emergencyContactDetails')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">

                <div class="w-full md:w-1/2">
                    <label for="currentAddress" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Current Address <strong class="text-[#f83434]">*</strong></label>
                    <textarea type="text" name="currentAddress" id="currentAddress" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Current Address" required>{{ !empty($newUserDetails->currentAddress) ? $newUserDetails->currentAddress:'' }}</textarea>
                    @error('currentAddress')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="permanentAddress" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Permanent Address <strong class="text-[#f83434]">*</strong></label>
                    <textarea R type="text" name="permanentAddress" id="permanentAddress" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Permanent Address" required>{{ !empty($newUserDetails->permanentAddress) ? $newUserDetails->permanentAddress :'' }}</textarea>
                    @error('permanentAddress')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">

                <div class="w-full md:w-1/2">
                    <label for="uploadPan" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Pan <strong class="text-[#f83434]">*</strong> <span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg,png and pdf format supported )</span></label>
                    <div class="relative flex flex-wrap gap-[10px]">
                        <img class="getpreviewImage" src="{{asset(!empty($newUserDetails->uploadPan) ? 'Image/'.$newUserDetails->uploadPan : 'assets/images/noimage.png')}}" width="70" height="70" class="w-[83px] h-[45px] rounded-[10px] object-cover shadow-[0_0_5px_rgba(0,0,0,0.3)]" />
                        
                        <div class="relative">
                            <label for="uploadPan" class="cursor-pointer w-[83px] h-[45px] rounded-[10px] flex items-center justify-center border border-dashed border-[#13103a4d] ">
                                <svg class="cursor-pointer" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.9395 8.95044H8.93945V13.9504C8.93945 14.5004 8.48945 14.9504 7.93945 14.9504C7.38945 14.9504 6.93945 14.5004 6.93945 13.9504V8.95044H1.93945C1.38945 8.95044 0.939453 8.50044 0.939453 7.95044C0.939453 7.40044 1.38945 6.95044 1.93945 6.95044H6.93945V1.95044C6.93945 1.40044 7.38945 0.950439 7.93945 0.950439C8.48945 0.950439 8.93945 1.40044 8.93945 1.95044V6.95044H13.9395C14.4895 6.95044 14.9395 7.40044 14.9395 7.95044C14.9395 8.50044 14.4895 8.95044 13.9395 8.95044Z" fill="#13103A" />
                                </svg>
                            </label>
                            <input type="file" name="uploadPan" id="uploadPan" class="previewImage w-0 opacity-0 absolute top-0 left-0"{{ empty($newUserDetails->uploadDrivingLicence) ? 'required':''}}>
                        </div>
                    </div>
                    @error('uploadPan') 
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="uploadAadhar" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Aadhar <strong class="text-[#f83434]">*</strong> <span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg,png and pdf format supported )</span></label>
                    <div class="relative flex flex-wrap gap-[10px]">
                        <img class="getpreviewImage" src="{{asset(!empty($newUserDetails->uploadAadhar) ? 'Image/'.$newUserDetails->uploadAadhar : 'assets/images/noimage.png')}}" width="70" height="70" class="w-[83px] h-[45px] rounded-[10px] object-cover shadow-[0_0_5px_rgba(0,0,0,0.3)]" />
                        <div class="relative">
                            <label for="uploadAadhar" class="cursor-pointer w-[83px] h-[45px] rounded-[10px] flex items-center justify-center border border-dashed border-[#13103a4d] ">
                                <svg class="cursor-pointer" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.9395 8.95044H8.93945V13.9504C8.93945 14.5004 8.48945 14.9504 7.93945 14.9504C7.38945 14.9504 6.93945 14.5004 6.93945 13.9504V8.95044H1.93945C1.38945 8.95044 0.939453 8.50044 0.939453 7.95044C0.939453 7.40044 1.38945 6.95044 1.93945 6.95044H6.93945V1.95044C6.93945 1.40044 7.38945 0.950439 7.93945 0.950439C8.48945 0.950439 8.93945 1.40044 8.93945 1.95044V6.95044H13.9395C14.4895 6.95044 14.9395 7.40044 14.9395 7.95044C14.9395 8.50044 14.4895 8.95044 13.9395 8.95044Z" fill="#13103A" />
                                </svg>
                            </label>
                            <input type="file" name="uploadAadhar" id="uploadAadhar" class="previewImage w-0 opacity-0 absolute top-0 left-0" {{empty($newUserDetails->uploadAadhar) ? 'required' :''}} >
                        </div>
                    </div>
                    @error('uploadAadhar')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="uploadDrivingLicence" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Driving Licence <strong class="text-[#f83434]">* <span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg,png and pdf format supported )</span></strong></label>
                    <div class="relative flex flex-wrap gap-[10px]">
                        <img class="getpreviewImage" src="{{asset(!empty($newUserDetails->uploadDrivingLicence) ? 'Image/'.$newUserDetails->uploadDrivingLicence : 'assets/images/noimage.png')}}" width="70" height="70" class="w-[83px] h-[45px] rounded-[10px] object-cover shadow-[0_0_5px_rgba(0,0,0,0.3)]" />
                        <div class="relative">
                            <label for="uploadDrivingLicence" class="cursor-pointer w-[83px] h-[45px] rounded-[10px] flex items-center justify-center border border-dashed border-[#13103a4d] ">
                                <svg class="cursor-pointer" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.9395 8.95044H8.93945V13.9504C8.93945 14.5004 8.48945 14.9504 7.93945 14.9504C7.38945 14.9504 6.93945 14.5004 6.93945 13.9504V8.95044H1.93945C1.38945 8.95044 0.939453 8.50044 0.939453 7.95044C0.939453 7.40044 1.38945 6.95044 1.93945 6.95044H6.93945V1.95044C6.93945 1.40044 7.38945 0.950439 7.93945 0.950439C8.48945 0.950439 8.93945 1.40044 8.93945 1.95044V6.95044H13.9395C14.4895 6.95044 14.9395 7.40044 14.9395 7.95044C14.9395 8.50044 14.4895 8.95044 13.9395 8.95044Z" fill="#13103A" />
                                </svg>
                            </label>
                            <input type="file" name="uploadDrivingLicence" id="uploadDrivingLicence" class="previewImage w-0 opacity-0 absolute top-0 left-0" {{empty($newUserDetails->uploadDrivingLicence) ? 'required' :''}}>
                        </div>
                    </div>
                    @error('uploadDrivingLicence')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.previewImage').on('change', function (event) {            
            var input = event.target;
            var previewContainer = $(this).parent().parent().find('.getpreviewImage');            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.hide();
                previewContainer.attr('src', '');
            }
        });
        
        // $('.daterangepicker-item').daterangepicker({
            
        //     opens: 'right',
        //     locale: {
        //         format: 'DD MMM YYYY'
        //     }
        // }, function(start, end, label) {
        //     console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        // });
        $('#startDate').on('click',function(){
            $(this).attr('type', 'text');
            $('.startdateShow').show();
         
        })
        $('#startDate').daterangepicker({
        singleDatePicker: true,  
        showDropdowns: true,     
        locale: {
            format: 'YYYY-MM-DD' 
        }
    });

 
  

      $('#endDate').on('click',function(){
            $(this).attr('type', 'text');
            $('.startdateShow').show();
         
        })
        $('#endDate').daterangepicker({
        format: 'YYYY-MM-DD' ,
        singleDatePicker: true, 
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD' 
        }
      });
    });
  
    $(document).ready(function() {
        $('.daterangepicker-startDate').daterangepicker({
            singleDatePicker: true, 
            opens: 'right',
            locale: {
                format: 'DD MMM YYYY' 
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
        });
    });

    $(document).ready(function() {
        $('.daterangepicker-endDate').daterangepicker({
            singleDatePicker: true, 
            opens: 'right',
            locale: {
                format: 'DD MMM YYYY' 
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
        });
    });
</script>
@endsection