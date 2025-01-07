
@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}</h3>
    </div>
    
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('users.adduser', ['id' => $newUser->id]) }}" enctype="multipart/form-data" class="py-[25px] px-[30px] space-y-[20px]">
            @csrf

            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name</label>
                    <input type="text" name="name" id="name" value="{{ $newUser->name }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Name">
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="w-full md:w-1/2">
                    <label for="role" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Role</label>
                    <select name="role" id="role" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value=""  >Select Role</option>
                        <option value="2" {{$newUser->role == 2 ? 'selected':''}}>Employee</option>
                        <option value="5" {{$newUser->role == 5 ? 'selected':''}}>Project Manager</option>
                    </select>
                    @error('role')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="fatherHusbandName" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Father/Husband Name</label>
                    <input type="text" name="fatherHusbandName" id="fatherHusbandName" value="{{ $newUserDetails->fatherHusbandName }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Company Name"  >
                    @error('fatherHusbandName')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="employeePhoto" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Photograph:</label>
                    <input type="file" name="employeePhoto" id="employeePhoto" value="{{ $newUserDetails->employeePhoto }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Employee Photo"  >
                    @error('employeePhoto')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="mobileNumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile Number</label>
                    <input type="number" name="mobileNumber" id="mobileNumber" value="{{ $newUser->mobile }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Mobile Number"  >
                    @error('mobileNumber')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="altMobile" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Mobile Number</label>
                    <input type="number" name="altMobile" id="altMobile" value="{{ $newUser->altNumber }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Alternate Mobile Number"  >
                    @error('altMobile')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                    <input type="text" name="email" id="email" value="{{ $newUser->email }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email"  >
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="qualification" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Qualification</label>
                    <input type="text" name="qualification" id="qualification" value="{{ $newUserDetails->qualification }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Qualification"  >
                    @error('qualification')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="repeater flex flex-col md:flex-row gap-[20px]">               
                <div class="repeater-default">
                    <div data-repeater-list="experince">
                        <div data-repeater-item>
                            @if (!empty($newUserExperiences))                                
                               
                                   
                                    <div class="form-group flex gap-[20px]">
                                        <div class="w-full md:w-1/2">
                                            <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Employer
                                            </label>
                                            <input type="text" name="employerName" id="employerName"
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                placeholder="Employer Name">                                    
                                        </div>
                                        <div class="w-full md:w-1/2">
                                            <label for="startDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Start Date
                                            </label>
                                            <input type="date" name="startDate" id="startDate"  
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                >                                    
                                        </div>
                                        <div class="w-full md:w-1/2">
                                            <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                End Date
                                            </label>
                                            <input type="date" name="endDate" id="endDate"  
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                >
                                        </div>
                                        <div class="col-sm-2 flex items-center">
                                            <span data-repeater-delete class="btn btn-danger btn-sm">
                                                <span class="glyphicon glyphicon-remove"></span> Delete
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group flex gap-[20px]">
                                        <div class="w-full md:w-1/2">
                                            <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Employer
                                            </label>
                                            <input type="text" name="employerName" id="employerName"  
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                placeholder="Employer Name">                                    
                                        </div>
                                        <div class="w-full md:w-1/2">
                                            <label for="startDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Start Date
                                            </label>
                                            <input type="date" name="startDate" id="startDate"  
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                >                                    
                                        </div>
                                        <div class="w-full md:w-1/2">
                                            <label for="endDate" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                End Date
                                            </label>
                                            <input type="date" name="endDate" id="endDate"  
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                >
                                        </div>
                                        <div class="col-sm-2 flex items-center">
                                            <span data-repeater-delete class="btn btn-danger btn-sm">
                                                <span class="glyphicon glyphicon-remove"></span> Delete
                                            </span>
                                        </div>
                                    </div>
                            @endif
                        </div>
                    </div>
                    <a data-repeater-create class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]"> Add More </a>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="skill" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Skills</label>
                    <input type="text" name="skill" id="skill" value="{{ $newUserDetails->skills }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                    @error('skill')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="keyResponsibilityArea" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Key Responsibility Area</label>
                    <input type="text" name="keyResponsibilityArea" id="keyResponsibilityArea" value="{{ $newUserDetails->keyResponsibilityArea }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                    @error('keyResponsibilityArea')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="keyPerformanceIndicator" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Key Performance Indicator</label>
                    <input type="text" name="keyPerformanceIndicator" id="keyPerformanceIndicator" value="{{ $newUserDetails->keyPerformanceIndicator }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                    @error('keyPerformanceIndicator')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="emergencyContactDetails" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Emergency Contact Details</label>
                    <input type="text" name="emergencyContactDetails" id="emergencyContactDetails" value="{{ $newUserDetails->emergencyContactDetails }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                    @error('emergencyContactDetails')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="currentAddress" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Current Address</label>
                    <textarea type="text" name="currentAddress" id="currentAddress" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Current Address">{{ $newUserDetails->currentAddress }}</textarea>
                    @error('currentAddress')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="permanentAddress" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Permanent Address</label>
                    <textarea type="text" name="permanentAddress" id="permanentAddress" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Permanent Address">{{ $newUserDetails->permanentAddress }}</textarea>
                    @error('permanentAddress')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="uploadPan" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Pan</label>
                    <input type="file" name="uploadPan" id="uploadPan" value="{{ $newUserDetails->uploadPan }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                    @error('uploadPan')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="uploadAadhar" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Aadhar</label>
                    <input type="file" name="uploadAadhar" id="uploadAadhar" value="{{ $newUserDetails->uploadAadhar }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                    @error('uploadAadhar')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="uploadDrivingLicence" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Driving Licence</label>
                    <input type="file" name="uploadDrivingLicence" id="uploadDrivingLicence" value="{{ $newUserDetails->uploadDrivingLicence }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
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

@endsection