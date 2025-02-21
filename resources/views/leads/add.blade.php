@php
    namespace App;
    use App\Models\ServiceStages;
@endphp
@extends('layouts.default')
@section('content')

<div>   
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Add Leads</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[10px] lg:rounded-[20px] mb-[30px]">
        <form method="POST" id="submitLeadForm" action="{{ route('leads.add',['id'=>$leadData->id])}}" enctype="multipart/form-data" class="py-[15px] px-[15px] lg:py-[25px] lg:px-[30px] space-y-[20px]">
            @csrf    
            <input type="hidden" name="savetype" id="savetype" value='0'/>  
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="source" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source</label>
                    <select name="source" id="source" class="allform-select2 showSourceListName w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required onchange="displayRequired(this)">
                        <option value="">Source Type</option>
                        @if ($sourceList && $sourceList->isNotEmpty())
                            @foreach ($sourceList as $sourceListData)
                                <option value="{{ $sourceListData->id }}" 
                                    {{ old('source', $leadData->source) == $sourceListData->id ? 'selected' : '' }}>
                                    {{ $sourceListData->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>                    
                </div>
                @php
                    $sourceTypeData = [];
                    $displayClass = 'hidden';
                @endphp

                @if (!empty($leadData))
                    @if ($leadData->source == 17 || $leadData->source == 18 || $leadData->source == 19)
                        @if ($leadData->source_id > 0)           
                            @php
                                $sourceTypeData = collect(getSourceTypeName($leadData->source));
                                $displayClass = '';
                            @endphp                        
                        @endif
                    @endif
                @endif
                <div class="sourceTypeNameDiv relative w-full md:w-1/2 {{$displayClass}}" id="source_type">
                    <label for="sourceTypeNameList" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source Type Name</label>
                    <select name="sourcetypenamelist" id="sourceTypeNameList" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none is_required">
                        @if ($sourceTypeData && $sourceTypeData->isNotEmpty())
                            @foreach ($sourceTypeData as $key => $value)
                                <option value="{{ $value->id }}" 
                                    {{ old('sourcetypenamelist', $leadData->source_id) == $value->id ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach                            
                        @endif
                    </select>
                    
                    <div class="loader sourceNameLoader flex items-center justify-center bg-[#ffffffa8] h-[45px] absolute top-[20px] left-[0] right-[0] m-auto hidden">
                        <span class="loader-1"> </span>   
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <label for="clientname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                    <input type="text" name="clientname" id="clientname" 
                        value="{{ old('clientname', !empty($leadData) ? $leadData->client_name : '') }}" 
                        class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                    required>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="{{ old('companyname') ? old('companyname') : (!empty($leadData) ? $leadData->company_name : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="scopeofbusiness" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Scope Of Business <strong class="text-[#f83434]">*</strong></label>
                    <select name="scopeofbusiness[]" id="scopeofbusiness" 
                        class="selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                        required multiple>
                        <option value="">Select Scope Of Business</option>                    
                        @if (!empty($scopeOfBussinessList) && $scopeOfBussinessList->isNotEmpty())
                            @foreach ($scopeOfBussinessList as $scopeOfBussinessListDetails)  
                                <option value="{{ $scopeOfBussinessListDetails->id }}" 
                                    @selected(in_array($scopeOfBussinessListDetails->id, old('scopeofbusiness', $leadData->business_scope ? explode(',', $leadData->business_scope) : [])))>
                                    {{ $scopeOfBussinessListDetails->name }}
                                </option>                      
                            @endforeach                                                            
                        @endif
                    </select>

                </div>
                
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="mobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile number</label>
                    <input type="text" data-id="{{$leadData->id}}" name="mobilenumber" id="mobilenumber" value="{{ old('mobilenumber') ? old('mobilenumber') : (!empty($leadData) ? $leadData->mobile_number : '')}}" class="checkDuplicateMobile w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                    <span class="mobile_exist_error text-[#df2727] text-[12px] hidden">This mobile number is already exists on user!</span>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                    <input type="text" name="email" id="email" value="{{ old('email') ? old('email') : (!empty($leadData) ? $leadData->email : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>                
            </div>
            <div>
                {{-- service repeater start --}}
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Services</label>
                <div class="leadServiceRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                    <div class="repeater-default">
                        <div data-repeater-list="leadRepeater" class="flex flex-col gap-[20px]">  
                            @if ($LeadTask && $LeadTask->isNotEmpty())                          
                                @foreach ($LeadTask as $serviceKey => $serviceVal)
                                    <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                                        <div class="w-[calc(100%-75px)] ">
                                            <input type="hidden" name="lead_task_id" value="{{$serviceVal->id}}">
                                            <div class="flex flex-col md:flex-row gap-[20px]">
                                                <div class="w-full md:w-1/2">
                                                    <select name="assign" id="assign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                        <option value="">Assign To Users</option>
                                                        @foreach ($userList as $userListData)
                                                            <option 
                                                                value="{{ $userListData->id }}" 
                                                                @selected(old('assign', $serviceVal->user_id) == $userListData->id)>
                                                                {{ $userListData->name }}
                                                            </option>
                                                        @endforeach                            
                                                    </select>
                                                    
                                                </div>
                                                <div class="w-full md:w-1/2">
                                                    <select name="serviceid" class="lead_service_id setSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                        <option value="">Service Name</option>
                                                        @if (count($serviceList) > 0)
                                                            @foreach ($serviceList as $serviceListData)
                                                                <option value="{{ $serviceListData->id }}" 
                                                                    @selected(old('serviceid', $serviceVal->service_id) == $serviceListData->id)>
                                                                    {{ $serviceListData->serviceName }}
                                                                </option>
                                                            @endforeach  
                                                        @endif
                                                    </select>
                                                    
                                                </div>
                                                <div class="relative w-full md:w-1/2">
                                                    <select name="subserviceid" class="getSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                        <option value="">Service Type</option>  
                                                        @if ($serviceVal->subservice_id)                                                           
                                                            @php 
                                                                $subServiceData = getSubService($serviceVal->service_id);
                                                            @endphp                                                      
                                                            @foreach ($subServiceData as $subServiceDataKey => $subServiceDataVal)                                                                
                                                                <option value="{{ $subServiceDataVal->id }}" 
                                                                    @selected(old('subserviceid', $serviceVal->subservice_id) == $subServiceDataVal->id)>
                                                                    {{ $subServiceDataVal->subServiceName }}
                                                                </option>                                                                
                                                            @endforeach    
                                                        @endif                                              
                                                    </select>
                                                    
                                                    <div class="loader serviceNameLoader flex items-center justify-center bg-[#ffffffa8] h-[45px] absolute top-[20px] left-[0] right-[0] m-auto hidden">
                                                        <span class="loader-1"> </span>   
                                                    </div>
                                                </div>
                                                <div class="w-full md:w-1/2 stageoftheservice">
                                                    <select name="stage_id" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none serviceStagesOption" required>
                                                        <option value="">Select Stage</option>
                                                        @php
                                                            $allStagesData = ServiceStages::where('service_id', $serviceVal->service_id)->get();
                                                        @endphp
                                                        @if ($allStagesData && $allStagesData->isNotEmpty())
                                                            @foreach ($allStagesData as $stageVal)
                                                                <option value="{{ $stageVal['id'] }}" 
                                                                    @selected(old('stage_id', $serviceVal->service_stage_id) == $stageVal['id'])>
                                                                    {{ $stageVal['title'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    
                                                </div>
                                                <div class="w-full md:w-1/2">
                                                                           
                                                    <div class="w-[100%] relative">
                                                        <input 
                                                            type="text" 
                                                            placeholder="Dead Line" 
                                                            name="taskdeadline" 
                                                            class="daterangepicker-taskdeadline daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                                                            value="{{ old('taskdeadline', !empty($serviceVal->leadTaskDetails) ? date('d M Y', strtotime($serviceVal->leadTaskDetails->dead_line)) : '') }}" 
                                                            autocomplete="off"
                                                        />                                                        
                                                    </div>     
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-[55px]">
                                            <span data-repeater-delete data-id="{{$serviceVal->id}}" class="deleteLeadRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
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
                                    <div class="w-[calc(100%-75px)] ">
                                        <input type="hidden" name="lead_task_id" value="0">
                                        <div class="flex flex-col md:flex-row gap-[20px]">
                                            <div class="w-full md:w-1/2">
                                                <select name="assign" id="assign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Assign To Users</option>
                                                    @foreach ($userList as $userListData)
                                                        <option value="{{ $userListData->id }}" 
                                                            @selected(old('assign') == $userListData->id)>
                                                            {{ $userListData->name }}
                                                        </option>
                                                    @endforeach                            
                                                </select>                                                
                                            </div>
                                            <div class="w-full md:w-1/2">
                                                <select name="serviceid" class="lead_service_id setSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Service Name</option>
                                                    @if (count($serviceList) > 0)
                                                        @foreach ($serviceList as $serviceListData)
                                                            <option value="{{ $serviceListData->id }}" 
                                                                @selected(old('serviceid') == $serviceListData->id)>
                                                                {{ $serviceListData->serviceName }}
                                                            </option>
                                                        @endforeach  
                                                    @endif
                                                </select>                                                
                                            </div>
                                            <div class="relative w-full md:w-1/2">
                                                <select name="subserviceid" class=" getSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Service Type</option>                                                
                                                </select>
                                                <div class="loader serviceNameLoader flex items-center justify-center bg-[#ffffffa8] h-[45px] absolute top-[0px] left-[0] right-[0] m-auto hidden">
                                                    <span class="loader-1"> </span>   
                                                </div>
                                            </div>
                                            <div class="w-full md:w-1/2 stageoftheservice">
                                                <select name="stage_id" class=" w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none serviceStagesOption" required>
                                                    <option value="">Select Stage</option>                                                   
                                                </select>
                                            </div>
                                            <div class="w-full md:w-1/2">                                                                        
                                                <div class="w-[100%] relative">
                                                    <input 
                                                        type="text" 
                                                        placeholder="Dead Line" 
                                                        name="taskdeadline" 
                                                        class="daterangepicker-taskdeadline daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                                                        value="{{ old('taskdeadline')}}" 
                                                        autocomplete="off"
                                                    >
                                                    
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
                {{-- service repeater end --}}               
            </div>
            
            {{-- multi attachment --}}
            <div>
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Attachments <span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg png and pdf format supported & max:2 MB)</span></label>
                <div class="leadAttachmentRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                    <div class="repeater-default">
                        <div data-repeater-list="leadAttachment" class="flex flex-col gap-[40px]"> 
                            @if (count($leadAttachment) > 0)
                                @foreach ($leadAttachment as $attachmentVal) 
                                    <div data-repeater-item class="flex  flex-wrap items-end gap-[20px]">
                                        <div class="w-[calc(100%-75px)] ">
                                            <input type="hidden" name="attachment_id" class="attachment_id" value="{{ $attachmentVal['id']}}">
                                            <div class="flex flex-col md:flex-row gap-[20px]">
                                                <div class="w-full md:w-1/2">
                                                    <div class="relative flex flex-wrap items-center gap-[10px]">
                                                        @php
                                                            $imagePath = !empty($attachmentVal['document']) ? 'uploads/leads/'.$leadData->id.'/'.$attachmentVal['document'] : 'assets/images/noimage.png';
                                                            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                                                        @endphp
                                                        <img src="{{ asset(strtolower($extension) === 'pdf' ? 'assets/images/pdf_logo.jpg' : $imagePath) }}" 
                                                            class="getpreviewImage w-[100%] max-w-[100px] rounded-[10px] object-cover" />                                                       
                                                        <div class="relative">
                                                            <input class="previewImage" type="file" name="attachmentFile" >                                                            
                                                        </div>
                                                        <div class="imageErrorMsg text-[12px] italic font-[400] text-[#e70e0e]"></div>
                                                    </div>
                                                </div>                                            
                                            </div>
                                        </div>
                                        <div class="w-[55px]">
                                            <span data-repeater-delete data-id="{{ $attachmentVal['id']}}" class="deleteAttachmentRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
                                                <span class="glyphicon glyphicon-remove"></span>
                                                <svg class="mx-auto" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.616 16C2.15533 16 1.771 15.846 1.463 15.538C1.155 15.23 1.00067 14.8453 1 14.384V2H0V1H4V0.230003H10V1H14V2H13V14.385C13 14.845 12.846 15.2293 12.538 15.538C12.23 15.8467 11.8453 16.0007 11.384 16H2.616ZM4.808 13H5.808V4H4.808V13ZM8.192 13H9.192V4H8.192V13Z" fill="#FF0000" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div> 
                                @endforeach
                                @else
                                <div data-repeater-item class="flex  flex-wrap items-end gap-[20px]">
                                    <div class="w-[calc(100%-75px)] ">
                                        <input type="hidden" name="attachment_id" class="attachment_id" value="0">
                                        <div class="flex flex-col md:flex-row gap-[20px]">
                                            <div class="w-full md:w-1/2">
                                                <div class="relative flex flex-wrap items-center gap-[10px]">
                                                    <img src="/assets/images/noimage.png" class="getpreviewImage w-[100%] max-w-[150px] rounded-[10px] object-cover" />
                                                    
                                                    <div class="relative">
                                                        <input class="previewImage" type="file" name="attachmentFile" >
                                                    </div>
                                                    <div class="imageErrorMsg text-[12px] italic font-[400] text-[#e70e0e]"></div>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                    <div class="w-[55px]">
                                        <span data-repeater-delete data-id="0" class="deleteAttachmentRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
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

            <div class="">
                <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
                <textarea name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">{{ old('description', !empty($leadData) ? $leadData->description : '') }}</textarea>

            </div>
            <div class="">
                <label for="msmem" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">MSMEM</label>
                <div class="flex flex-wrap gap-[20px]">
                    <div>
                        <input type="radio" name="msmem" id="msmem" value="1" 
                            @checked(old('msmem', $leadData->msmem) == 1)>
                        <label for="msmem" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="msmem" id="msmem2" value="0" 
                            @checked(old('msmem', $leadData->msmem) == 0)>
                        <label for="msmem2" class="text-[12px] font-[400] leading-[14px] text-[#000000]">No</label>
                    </div>
                </div>
                
            </div>
            <div class="">
                <button type="button" name="save" class="lead_submit_btn text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
                <button type="button" name="saveAssign" class="lead_submit_btn text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save & Assign</button>
            </div>
            
        </form>
    </div>
</div>
<script>
   
    $(document).on('click','.lead_submit_btn',function(){

        let form = $("#submitLeadForm")[0];
        if (!form.checkValidity()) {
            form.reportValidity(); 
            return;
        }
        if($(this).attr('name') == 'saveAssign'){
            swal("Are you sure you want to process this lead to the assigned stage? You won't be able to edit the lead anymore once confirmed.", {
            buttons: {
                cancel: "Cancel",
                catch: {
                    text: "Yes, proceed!",
                    value: "proceed",
                },
            },
            }).then((value) => {
                switch (value) {
                    case "proceed":
                        $("#submitLeadForm").find("#savetype").val('1');
                        $("#submitLeadForm").trigger("submit");
                    break;
                    default:
                    swal("No Action Performed");
                }
            });
        }else{
            $("#submitLeadForm").trigger("submit");
        }
    });
    
    $(document).on('change','.previewImage' ,function () { 
        var file = this.files[0]; 
        var maxSize = 2 * 1024 * 1024;
        var allowedExtensions = ["jpg", "jpeg", "png", "pdf"];  
        if (file) {
            var fileSize = file.size;
            var fileName = file.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();

            // Check file extension
            if (!allowedExtensions.includes(fileExtension)) {
                $(this).parent().parent().find('.imageErrorMsg').text("Invalid file type. Allowed types: " + allowedExtensions.join(", "));
                $(this).val(""); // Clear file input
                return false;
            }

            // Check file size
            if (fileSize > maxSize) {
                $(this).parent().parent().find('.imageErrorMsg').text("File size exceeds 2MB limit.");
                $(this).val(""); // Clear file input
                return false;
            }
            var input = event.target;
            var previewContainer = $(this).parent().parent().find('.getpreviewImage');   
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    if(fileExtension == 'pdf'){
                        previewContainer.attr('src', '/assets/images/pdf_logo.jpg').show();
                    }else{
                        previewContainer.attr('src', e.target.result).show();
                    }
                    $('.imageErrorMsg').text('');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.hide();
                previewContainer.attr('src', '');
            }
        }                  
       
    });
   
    $(document).on('change','.setSubService',function(){
        var serviceId = $(this).val();
        $(this).parent().parent().find('.serviceNameLoader').removeClass('hidden');
        
        var e = $(this);
        $.ajax({
            method:'POST',
            url:"{{ route('lead.subservice')}}",
            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dataType:'json',
            data:{
                serviceId:serviceId
            },
            success:function(res){
                e.parent().parent().find('.getSubService').html(res.data);
            }
        });

        //get stages
        $.ajax({
            method:'POST',
            url:"{{ route('serviceStages')}}",
            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data:{
                serviceId:serviceId
            },
            dataType:'json',
            success:function(res){
                e.parent().parent().find('.stageoftheservice').find('.serviceStagesOption').html(res.options);
                e.parent().parent().find('.serviceNameLoader').addClass('hidden');

            }
        });
    });

    $(document).ready(function(){
        // $('.showSourceListName').trigger('change');
        // $('.setSubService').trigger('change');

    });
    
    $(document).on('change','.showSourceListName',function(){
        var value = $(this).val();
        if(value == 17 || value == 18 || value == 19){         
            $('.sourceTypeNameDiv').css('display','block');
            $('.sourceNameLoader').removeClass('hidden');
            $.ajax({
                method:'POST',
                url:"{{ route('lead.getsourcetypename')}}",
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token()}}'
                },
                dataType:'json',
                data:{
                    value:value
                },
                success:function(res){
                    $('.sourceTypeNameDiv').find('#sourceTypeNameList').html(res.data);
                    $('.sourceNameLoader').addClass('hidden');
                }
            })
        }else{
            $('.sourceTypeNameDiv').css('display','none');
        }
    });

    $(document).ready(function() {
        $('.daterangepicker-taskdeadline').daterangepicker({
            singleDatePicker: true, 
            opens: 'right',
            locale: {
                format: 'DD MMM YYYY' 
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
        });
    });

    function displayRequired(e){
        const isRequireed = $('.is_required');
        const sourceType = $('#source_type');
        const value = $(e).val();

        sourceType.hide();
        isRequireed.attr('required', false);

        console.log(value);
        if(value == 17 || value == 18 || value == 19){
            sourceType.show();
            isRequireed.attr('required', true);
        }
        
    }
    $(document).on('keyup','.checkDuplicateMobile',function(){
        if($(this).val().length >=10){
            let id = $(this).data('id');
            let val = $(this).val();
            let e = $(this);
            $.ajax({
                method:'POST',
                url:"{{ route('lead.checkDuplicate')}}",
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                data:{
                    id:id,
                    val:val
                },
                success:function(res){
                    if(res.exists){
                        e.val('');
                        $('.mobile_exist_error').removeClass('hidden');
                    }else{
                        $('.mobile_exist_error').addClass('hidden');
                    }
                }
            });
        }
        
    });
    
</script>
@stop