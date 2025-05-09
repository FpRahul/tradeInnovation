@php
    namespace App;
    use App\Models\ServiceStages;
@endphp
@extends('layouts.default')
@section('content')

<div>   
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Add Leads</h3>
        <buttton class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]" data-modal-target="assignUserModal" data-modal-toggle="assignUserModal">Client Exist</button>
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
                                    {{ $value->name .'('.$value->mobile.')' }}
                                </option>
                            @endforeach                            
                        @endif
                    </select>
                    
                    <div class="loader sourceNameLoader flex items-center justify-center bg-[#ffffffa8] h-[45px] absolute top-[20px] left-[0] right-[0] m-auto hidden">
                        <span class="loader-1"> </span>   
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <label for="firm" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Firm</label>
                    <select name="firm" id="firm" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">firm Type</option>
                        @if ($firmList && $firmList->isNotEmpty())
                            @foreach ($firmList as $firmListData)
                                <option value="{{ $firmListData->id }}" 
                                    {{ old('firm', $leadData->firm) == $firmListData->id ? 'selected' : '' }}>
                                    {{ $firmListData->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>                    
                </div>

                
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="clientname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                    <input type="text" name="clientname" id="clientname" value="{{ ucwords(old('clientname') ? old('clientname') : (!empty($leadData) ? $leadData->client_name : ''))}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="{{ ucwords(old('companyname') ? old('companyname') : (!empty($leadData) ? $leadData->company_name : ''))}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="scopeofbusiness" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Scope Of Business <strong class="text-[#f83434]">*</strong></label>
                    <select name="scopeofbusiness[]" id="scopeofbusiness" 
                        class="scopeOfBusinessSelect selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
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
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="otherScopeOfBusinessMain w-full md:w-1/2 hidden">
                    <label for="otherscopeofbusiness" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Other Scope</label>
                    <input type="text" class="otherscopeofbusiness w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" name="otherscopeofbusiness" value="">
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="mobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile number</label>
                    <input type="text" data-id="{{$leadData->id}}" name="mobilenumber" id="mobilenumber" value="{{ old('mobilenumber') ? old('mobilenumber') : (!empty($leadData) ? $leadData->mobile_number : '')}}" class="checkDuplicateMobile w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                    <span class="mobile_exist_error text-[#df2727] text-[12px] hidden">This mobile number is already exists on user!</span>
                    <span class="mobile_length_error text-[#df2727] text-[12px] hidden">Mobile number must be 10 digits long!</span>
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
                                    @if ($serviceVal->leadTaskDetails->status == 0)
                                        <div data-repeater-item class="repeater-item flex flex-wrap items-end gap-[20px]">
                                            <div class="w-[calc(100%-75px)] ">
                                                <input type="hidden" name="lead_task_id" value="{{$serviceVal->id}}">
                                                <div class="w-full outline-[1px] outline-dashed outline-[#ccc] p-[10px] rounded-[5px] flex flex-wrap gap-[5px] lg:gap-[10px] xl:gap-[15px]">
                                                    <div class="w-full">
                                                        <label for="client_type" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Type</label>
                                                        <div class="flex flex-wrap gap-[20px] mt-[20px]">
                                                            <div class="flex items-center gap-[10px]">
                                                                <input type="radio" name="client_type" id="client_type" class="client_type" value="1" 
                                                                    @checked(old('client_type', $serviceVal->serviceDetails->client_status) == 1) checked>
                                                                <label for="client_type" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Applicant</label>
                                                            </div>
                                                            <div class="flex items-center gap-[10px]">
                                                                <input type="radio" name="client_type" id="client_type2" class="client_type" value="2" 
                                                                    @checked(old('client_type', $serviceVal->serviceDetails->client_status) == 2)>
                                                                <label for="client_type2" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Opponent</label>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <div class="w-full  md:w-[32%] lg:w-[32%]">
                                                        <label class="block mb-[5px] text-[14px] font-[400]">Project Manager</label>
                                                        <select name="projectmanager" id="projectmanager" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                            <option value="">Project Manager</option>
                                                            @foreach ($projectManagerList as $projectManagerListData)
                                                                <option value="{{ $projectManagerListData->id }}" 
                                                                    @selected(
                                                                        old('projectmanager', $serviceVal->serviceDetails->project_manager_id) == $projectManagerListData->id ||
                                                                        ($authDetails->role == 4 && $authDetails->id == $projectManagerListData->id)
                                                                    )
                                                                >
                                                                    {{ $projectManagerListData->name }}
                                                                </option>
                                                            @endforeach                            
                                                        </select>                                                
                                                    </div>
                                                    <div class="w-full  md:w-[32%] lg:w-[32%]">
                                                        <label class="block mb-[5px] text-[14px] font-[400]">Service Name</label>
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
                                                    <div class="relative w-full  md:w-[32%] lg:w-[32%]">
                                                        <label class="block mb-[5px] text-[14px] font-[400]">Service Type</label>
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
                                                        
                                                        <div class="loader serviceNameLoader flex items-center justify-center bg-[#ffffffa8] h-[45px] absolute top-[26px] left-[0] right-[0] m-auto hidden">
                                                            <span class="loader-1"> </span>   
                                                        </div>
                                                    </div>
                                                    <div class="w-full  md:w-[32%] lg:w-[32%]">
                                                        <label class="block mb-[5px] text-[14px] font-[400]">Assign To Employee</label>
                                                        <select name="assign" id="assign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                            <option value="">Assign To Employee</option>
                                                            @foreach ($userList as $userListData)
                                                                <option 
                                                                    value="{{ $userListData->id }}" 
                                                                    @selected(old('assign', $serviceVal->user_id) == $userListData->id)>
                                                                    {{ $userListData->name }}
                                                                </option>
                                                            @endforeach                            
                                                        </select>                                                    
                                                    </div>
                                                    <div class="w-full  md:w-[32%] lg:w-[32%] stageoftheservice">
                                                        <label class="block mb-[5px] text-[14px] font-[400]">Service Stage</label>
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
                                                    <div class="w-full  md:w-[32%] lg:w-[32%]">
                                                        <label class="block mb-[5px] text-[14px] font-[400]">Dead Line</label>
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
                                                    
                                                    {{-- start service details --}}
                                                    <div class="eachServiceDetails flex flex-wrap w-full gap-[15px] border-[1px] border-[#ccc] outline-[#ccc] p-[10px] rounded-[5px] {{ isset($serviceVal->service_id) && $serviceVal->service_id == 1 || $serviceVal->service_id == 2 ? '' : 'hidden' }}">

                                                        <h2 class="w-full text-[22px] leading-[25px] mb-[5px] font-[600]">Details</h2>
                                                        <div class="w-full md:w-[32%] lg:w-[32%]">
                                                            <label class="block mb-[5px] text-[14px] font-[400]">Risk Class</label>
                                                            <select name="classrule"
                                                                    class="classrule w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                                    >
                                                            
                                                                <option value="">Risk Class</option>
                                                                @php
                                                                    $selectedValues = explode(',', old('classrule', $serviceVal->serviceDetails->class_rule ?? ''));
                                                                @endphp
                                                            
                                                                @for ($i = 1; $i <= 45; $i++)
                                                                    <option value="{{$i}}" @if(in_array($i, $selectedValues)) selected @endif>{{$i}}</option>
                                                                @endfor                         
                                                            </select>
                                                                                                    
                                                        </div>                                                
                                                        <div class="w-full md:w-[32%] lg:w-[32%]">
                                                            <label class="appliedForText block mb-[5px] text-[14px] font-[400]">
                                                                @if ($serviceVal->service_id == 1)
                                                                Trademark Applied For
                                                                @elseif ($serviceVal->service_id == 2)
                                                                Patent Applied For
                                                                @endif
                                                                </label>
                                                            <input type="text" name="appliedfor" 
                                                                value="{{ isset($serviceVal->serviceDetails) ? $serviceVal->serviceDetails->applied_for : '' }}" 
                                                                class="appliedfor w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                                >                                                
                                                        </div>
                                                        <div class="applicationNumberHide w-full md:w-[32%] lg:w-[32%] {{$serviceVal->subservice_id == 1 || $serviceVal->subservice_id == 10 ? 'hidden':''}}">
                                                            <label class="block mb-[5px] text-[14px] font-[400]">Application Number</label>
                                                            <input type="text" name="applicationNumber" 
                                                                value="{{ isset($serviceVal->serviceDetails) ? $serviceVal->serviceDetails->application_number : '' }}" 
                                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                                >                                                                                                 
                                                        </div>
                                                        <div class="w-full md:w-[32%] lg:w-[32%]">
                                                            @php
                                                                $logoPath = !empty($serviceVal->serviceDetails->service_logo) ? 'uploads/leads/'.$leadData->id.'/'.$serviceVal->serviceDetails->service_logo : 'assets/images/noimage.png';
                                                            @endphp
                                                        
                                                                <label class="block mb-[5px] text-[14px] font-[400]">Logo</label>
                                                                <div class="flex w-full items-center gap-[5px]">
                                                                    <img src="{{ asset($logoPath)}}" class="getpreviewlogo w-[30px] h-[30px] max-w-[30px] max-h-[30px] object-cover border-[1px] border-[#999] rounded-[5px]" />   
                                                                    <div class="relative prviewServ">
                                                                        <input class="previewServiceLogo  max-w-[80%] text-[14px] p-[8px]" type="file" name="serviceLogo" >                                                            
                                                                    </div>       
                                                                </div>                                                         
                                                            <div class="logoErrorMsg text-[12px] italic font-[400] text-[#e70e0e]"></div>                                             
                                                        </div>
                                                        <div class="w-full md:w-[32%] lg:w-[32%]">
                                                            <label class="block mb-[5px] text-[14px] font-[400]">Filing Mode</label>                                                           
                                                                <select name="filingmode" id="filingmode"
                                                                    class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                                                                    required >
                                                                    
                                                                    <option disabled>Select Filing Mode</option>                    

                                                                    @if (!empty($filingModeList) && $filingModeList->isNotEmpty())
                                                                        @foreach ($filingModeList as $filingModeListDetails)
                                                                            <option value="{{ $filingModeListDetails->id }}"
                                                                                @selected(old('filingmode', $serviceVal->serviceDetails->filing_mode) == $filingModeListDetails->id)>
                                                                                {{ $filingModeListDetails->name }}
                                                                            </option>
                                                                        @endforeach                                                            
                                                                    @endif

                                                                </select>
                                   
                                                        </div>   
                                                        <div class="w-full  md:w-[32%] lg:w-[32%]">  
                                                            <label class="block mb-[5px] text-[14px] font-[400]">Filing Date</label>                                                                      
                                                            <div class="w-[100%] relative">                                                            
                                                                <input 
                                                                    type="text" 
                                                                    placeholder="Filing Date" 
                                                                    name="filingdate" 
                                                                    class="filingdate daterangepicker-taskdeadline daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                                                                    value="{{ old('taskdeadline', $serviceVal->serviceDetails->filing_date ?? '') }}"
                                                                    autocomplete="off"
                                                                >                                                            
                                                            </div>     
                                                        </div>
                                                    
                                                    </div>
                                                    {{-- end service details --}}
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
                                    @endif
                                @endforeach 
                            @else
                           
                                <div data-repeater-item class="repeater-item flex flex-wrap items-end gap-[20px]">
                                    <div class="w-[calc(100%-75px)] ">
                                        <input type="hidden" name="lead_task_id" value="0">
                                        <div class="w-full outline-[1px] outline-dashed outline-[#ccc] p-[10px] rounded-[5px] flex flex-wrap gap-[5px] lg:gap-[10px] xl:gap-[15px]">
                                            <div class="w-full">
                                                <label for="client_type" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Type</label>
                                                <div class="flex flex-wrap gap-[20px] mt-[20px]">
                                                    <div class="flex items-center gap-[10px]">
                                                        <input type="radio" name="client_type" id="client_type" class="client_type" value="1" checked>
                                                        <label for="client_type" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Applicant</label>
                                                    </div>
                                                    <div class="flex items-center gap-[10px]">
                                                        <input type="radio" name="client_type" id="client_type2" class="client_type" value="2">
                                                        <label for="client_type2" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Opponent</label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="w-full md:w-[32%] lg:w-[32%]">
                                                <label class="block mb-[5px] text-[14px] font-[400]">Project Manager</label>
                                                <select name="projectmanager" id="projectmanager" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Project Manager</option>
                                                    @foreach ($projectManagerList as $projectManagerListData)
                                                        <option value="{{ $projectManagerListData->id }}" 
                                                            @selected(
                                                            ($authDetails->role == 4 && $authDetails->id == $projectManagerListData->id) ||
                                                            old('projectmanager') == $projectManagerListData->id
                                                        )>                                                            
                                                            {{ $projectManagerListData->name }}
                                                        </option>
                                                    @endforeach                            
                                                </select>                                                
                                            </div>
                                            <div class="w-full md:w-[32%] lg:w-[32%]">
                                                <label class="block mb-[5px] text-[14px] font-[400]">Service Name</label>
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
                                            <div class="relative w-full  md:w-[32%] lg:w-[32%]">
                                                <label class="block mb-[5px] text-[14px] font-[400]">Service Type</label>
                                                <select name="subserviceid" class=" getSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Service Type</option>                                                
                                                </select>
                                                <div class="loader serviceNameLoader flex items-center justify-center bg-[#ffffffa8] h-[45px] absolute top-[26px] left-[0] right-[0] m-auto hidden">
                                                    <span class="loader-1"> </span>   
                                                </div>
                                            </div>
                                            <div class="w-full  md:w-[32%] lg:w-[32%]">
                                                <label class="block mb-[5px] text-[14px] font-[400]">Assign To Employee</label>
                                                <select name="assign" id="assign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Assign To Employee</option>
                                                    @foreach ($userList as $userListData)
                                                        <option value="{{ $userListData->id }}" 
                                                            @selected(old('assign') == $userListData->id)>
                                                            {{ $userListData->name }}
                                                        </option>
                                                    @endforeach                            
                                                </select>                                                
                                            </div>
                                            <div class="w-full  md:w-[32%] lg:w-[32%] stageoftheservice">
                                                <label class="block mb-[5px] text-[14px] font-[400]">Service Stage</label>
                                                <select name="stage_id" class=" w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none serviceStagesOption" required>
                                                    <option value="">Select Stage</option>                                                   
                                                </select>
                                            </div>
                                            <div class="w-full  md:w-[32%] lg:w-[32%]">  
                                                <label class="block mb-[5px] text-[14px] font-[400]">Dead Line</label>                                                                      
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
                                                                                      

                                            {{-- start service details --}}
                                            <div class="eachServiceDetails flex flex-wrap w-full gap-[15px] border-[1px] border-[#ccc] outline-[#ccc] p-[10px] rounded-[5px] hidden">
                                                <h2 class="w-full text-[22px] leading-[25px] mb-[5px] font-[600]">Details</h2>
                                                <div class="w-full md:w-[32%] lg:w-[32%]">
                                                    <label class="block mb-[5px] text-[14px] font-[400]">Risk Class</label>
                                                    <select name="classrule" 
                                                            class="classrule w-full h-[55px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                            >
                                                        <option value="">Risk Class</option>
                                                        @for ($i=1;$i <= 45;$i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                        @endfor                         
                                                    </select>                                                
                                                </div>
                                                <div class="w-full md:w-[32%] lg:w-[32%]">
                                                    <label class="appliedForText block mb-[5px] text-[14px] font-[400]">Trademark Applied For</label>
                                                    <input type="text" name="appliedfor" id="appliedfor" value="" class="appliedfor w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >                                                
                                                </div>      
                                                <div class="applicationNumberHide w-full md:w-[32%] lg:w-[32%]">
                                                    <label class="block mb-[5px] text-[14px] font-[400]">Application Number</label>
                                                    <input type="text" name="applicationNumber" 
                                                        value="" 
                                                        class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                                                        > 
                                                                                            
                                                </div>                                      
                                                <div class="w-full md:w-[32%] lg:w-[32%]">
                                                    <label class="block mb-[5px] text-[14px] font-[400]">Logo</label>
                                                    <div class="flex items-center gap-[5px]">
                                                        <img src="{{ asset('assets/images/noimage.png')}}" class="getpreviewlogo w-[30px] h-[30px] max-w-[30px] max-h-[30px] object-cover border-[1px] border-[#999] rounded-[5px]" />   
                                                        <div class="relative prviewServ">
                                                            <input class="previewServiceLogo  max-w-[80%] text-[14px] p-[8px]" type="file" name="serviceLogo" >                                                            
                                                        </div>       
                                                    </div>                                             
                                                
                                                    <div class="logoErrorMsg text-[12px] italic font-[400] text-[#e70e0e]"></div>                                             
                                                </div>
                                                <div class="w-full md:w-[32%] lg:w-[32%]">
                                                    <label class="block mb-[5px] text-[14px] font-[400]">Filing Mode</label>
                                                    <select name="filingmode" id="filingmode"
                                                    class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                                                    required>
                                                    
                                                    <option disabled>Select Filing Mode</option>                    

                                                    @if (!empty($filingModeList) && $filingModeList->isNotEmpty())
                                                        @foreach ($filingModeList as $filingModeListDetails)
                                                            <option value="{{ $filingModeListDetails->id }}"
                                                                >
                                                                {{ $filingModeListDetails->name }}
                                                            </option>
                                                        @endforeach                                                            
                                                    @endif

                                                </select>
                                                                                            
                                                </div>  
                                                <div class="w-full  md:w-[32%] lg:w-[32%]">  
                                                    <label class="block mb-[5px] text-[14px] font-[400]">Filing Date</label>                                                                      
                                                    <div class="w-[100%] relative">
                                                        
                                                        <input 
                                                            type="text" 
                                                            placeholder="Filing Date" 
                                                            name="filingdate" 
                                                            class="filingdate daterangepicker-taskdeadline daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                                                            value="{{ old('taskdeadline')}}" 
                                                            autocomplete="off"
                                                        >
                                                        
                                                    </div>     
                                                </div>
                                               
                                            </div>

                                            {{-- end service details --}}
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
                <textarea name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">{{ ucwords(old('description', !empty($leadData) ? $leadData->description : '')) }}</textarea>

            </div>
            <div class="">
                <label for="msmem" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">MSME Registered Unit</label>
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
                {{-- <button type="button" name="save" class="lead_submit_btn text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button> --}}
                <button type="button" name="saveAssign" class="lead_submit_btn text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save & Assign</button>
            </div>
            
        </form>
    </div>
</div>
<div id="assignUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[100%)] max-h-full bg-[rgba(0,0,0,0.6)] ">
    <div class="relative p-4 w-full max-w-[780px] max-h-full m-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-[20px] shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:px-5 md:py-[20px] border-b border-[#0000001A] rounded-t dark:border-gray-600">
                <h3 class="text-[14px] font-[400] leading-[17px] text-[#000000] dark:text-white">
                   Create
                </h3>
                <button type="button" class="bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="assignUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-[20px]">
                <form method="POST" action={{ route('lead.existedClientDetail')}} class="space-y-[20px]">
                    @csrf 
                    <div class="flex flex-col md:flex-row gap-[20px]">                        
                        <div class="w-full">
                            <label for="clientId" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                            <select name="clientId" id="clientId" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                                <option value="">Select Client</option>
                                @if ($clientList && $clientList->isNotEmpty())
                                    @foreach ($clientList as $clVal)
                                        <option value="{{$clVal->id}}">{{$clVal->name}} ( {{$clVal->email}} )</option>
                                    @endforeach
                                @endif            
                            </select>                    
                        </div>
                    </div>
                   
                    <div class="flex justify-end gap-[15px]">                        
                        <button type="submit" data-id="" data-type="" data-name="" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
                    </div>
                </form>
            </div>
        </div>
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
   
    $(document).on('change','.previewServiceLogo' ,function () { 
        var file = this.files[0]; 
        var maxSize = 2 * 1024 * 1024;
        var allowedExtensions = ["jpg", "jpeg", "png", "pdf"];  
        if (file) {
            var fileSize = file.size;
            var fileName = file.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();

            // Check file extension
            if (!allowedExtensions.includes(fileExtension)) {
                $(this).parent().parent().parent().find('.logoErrorMsg').text("Invalid file type. Allowed types: " + allowedExtensions.join(", "));
                $(this).val(""); // Clear file input
                return false;
            }

            // Check file size
            if (fileSize > maxSize) {
                $(this).parent().parent().parent().find('.logoErrorMsg').text("File size exceeds 2MB limit.");
                $(this).val(""); // Clear file input
                return false;
            }
            var input = event.target;
            var previewContainer = $(this).parent().parent().find('.getpreviewlogo');   
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    if(fileExtension == 'pdf'){
                        previewContainer.attr('src', '/assets/images/pdf_logo.jpg').show();
                    }else{
                        previewContainer.attr('src', e.target.result).show();
                    }
                    $('.logoErrorMsg').text('');
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
        let clientType = $(this).parent().parent().find('input[name^="leadRepeater"][name$="[client_type]"]:checked').val();
        if(serviceId == 1 || serviceId == 2){
            $(this).parent().parent().find('.classrule').attr('required',true);
            $(this).parent().parent().find('.appliedfor').attr('required',true);
            $(this).parent().parent().find('.previewServiceLogo').attr('required',true);
            $(this).parent().parent().find('.filingmode').attr('required',true);
            $(this).parent().parent().find('.filingdate').attr('required',true);
            $(this).parent().parent().find('.eachServiceDetails').removeClass('hidden');
        }else{
            $(this).parent().parent().find('.classrule').attr('required',false);
            $(this).parent().parent().find('.appliedfor').attr('required',false);
            $(this).parent().parent().find('.previewServiceLogo').attr('required',false);
            $(this).parent().parent().find('.filingmode').attr('required',false);
            $(this).parent().parent().find('.filingdate').attr('required',false);
            $(this).parent().parent().find('.eachServiceDetails').addClass('hidden');
        }
        if(serviceId == 1 ){
            $(this).parent().parent().find('.appliedForText').text('Trademark Applied For');
        }
        if(serviceId == 2){
            $(this).parent().parent().find('.appliedForText').text('Patent Applied For');
        }
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
                serviceId:serviceId,
                clientType:clientType
            },
            success:function(res){
                e.parent().parent().find('.getSubService').html(res.data);
                e.parent().parent().find('.serviceNameLoader').addClass('hidden');
            }
        });

        //get stages
        // $.ajax({
        //     method:'POST',
        //     url:"{{ route('serviceStages')}}",
        //     headers:{
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     data:{
        //         serviceId:serviceId
        //     },
        //     dataType:'json',
        //     success:function(res){
        //         e.parent().parent().find('.stageoftheservice').find('.serviceStagesOption').html(res.options);
        //         e.parent().parent().find('.serviceNameLoader').addClass('hidden');

        //     }
        // });
    });

    $(document).on('click','.client_type',function(){
        let clientType = $(this).val();
        let serviceId = $(this).parent().parent().parent().parent().find('.lead_service_id').val();        
        if(serviceId > 0){
            $(this).parent().parent().parent().parent().find('.serviceNameLoader').removeClass('hidden');        
            var e = $(this);
            $.ajax({
                method:'POST',
                url:"{{ route('lead.subservice')}}",
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType:'json',
                data:{
                    serviceId:serviceId,
                    clientType:clientType
                },
                success:function(res){
                    e.parent().parent().parent().parent().find('.getSubService').html(res.data);
                    e.parent().parent().parent().parent().find('.serviceNameLoader').addClass('hidden');
                }
            });
        }else{
            alert("You must select a service name!");
        }
    });

    $(document).on('change','.getSubService',function(){
        let subServiceId = $(this).val();
        if(subServiceId == 1 || subServiceId == 10){
            $(this).parent().parent().find('.applicationNumberHide').addClass('hidden');
        }else{
            $(this).parent().parent().find('.applicationNumberHide').removeClass('hidden');
        }
        let serviceId = $(this).parent().parent().find('.lead_service_id').val();
        var e = $(this);
        $.ajax({
            method:'POST',
            url:"{{ route('serviceStages')}}",
            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data:{
                serviceId:serviceId,
                subServiceId:subServiceId
            },
            dataType:'json',
            success:function(res){
                e.parent().parent().find('.stageoftheservice').find('.serviceStagesOption').html(res.options);
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

    $(document).on('keyup', '.checkDuplicateMobile', function (e) {
        let cleanVal = $(this).val().replace(/\D/g, '');
        if (cleanVal.length > 10) {
            cleanVal = cleanVal.substring(0, 10);
        }
        $(this).val(cleanVal);
        $('.mobile_length_error').addClass('hidden');
        if (cleanVal.length === 10) {
            let id = $(this).data('id');
            let val = cleanVal;
            let element = $(this);

            $.ajax({
                method: 'POST',
                url: "{{ route('user.checkDuplicate') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    id: id,
                    val: val
                },
                success: function (res) {
                    if (res.exists) {
                        element.val('');
                        $('.mobile_exist_error').removeClass('hidden');
                    } else {
                        $('.mobile_exist_error').addClass('hidden');
                    }
                }
            });
        }
    });

    $(document).on('change', '.scopeOfBusinessSelect', function () {
        let hiddenDiv = $(this).parent().parent().find('.otherScopeOfBusinessMain'); 
        let selectedValues = $(this).val();
        if (Array.isArray(selectedValues) && selectedValues.includes('other')) {
            hiddenDiv.removeClass('hidden');
        }else {
            hiddenDiv.addClass('hidden');
        }
    });
    
  

</script>
@stop