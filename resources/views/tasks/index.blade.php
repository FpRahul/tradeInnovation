@extends('layouts.default')
@section('content')
<?php 
use App\Models\LeadTask;
use App\Models\Service;
use App\Models\Lead;
use App\Models\SubService;
use App\Models\ServiceDetail;
?>
<div>

    <div class="flex items-center justify-between mb-[20px]">
        <div>
            <h3 class="text-[18px] md:text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Manage Task</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>Home</li> /
                <li class="text-gray">Tasks</li>
            </ul>
        </div>
    </div>
    <div class="hidden filterToglle">
        <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden ">
           

            <div class="w-full py-[15px] md:py-[25px] px-[15px] md:px-[20px] gap-[10px] flex md:flex-wrap items-end justify-between mb-[40px]">
                <form action="" id="filterForm" class="w-full flex  gap-[30px]">
                    <div class="w-full flex items-center gap-[30px]">
                        <div class="w-full flex  md:flex-wrap items-end gap-[30px]">
                            

                            <!-- Lead ID Select (Increased width to 5/12) -->

                                <div class="w-full md:w-[32%]">
                                    <label for="leadId" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                                    <select name="leadId" id="leadId" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                        <option value="" selected>Select Client Name</option>
                                        @if(!$DistinctleadId->isEmpty())
                                            @foreach ($DistinctleadId as $leadID)
                                               <option value="{{ $leadID->client_id }}" @selected(isset($leadParam) && $leadParam == $leadID->client_id)>  
                                                    {{ $leadID->client_name }} - {{ $leadID->mobile_number }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- service --}}
                                <div class="w-full md:w-[32%]  service_id ">
                                    <label for="service_id" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Services</label>
                                    <select name="service_id" id="service_id" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                        <option value="">Select Service</option>
                                        @if(isset($leadParam))
                                        @php
                                            $lead_ids = Lead::where('client_id', $leadParam)
                                                            ->pluck('id');
                                            $serviceIds = LeadTask::whereIn('lead_id', $lead_ids)
                                                                ->groupBy('service_id')
                                                                ->pluck('service_id');
                                            $services = Service::whereIn('id', $serviceIds)->get();
                                        @endphp
                                        @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ $serviceParam == $service->id ? 'selected' : '' }}>{{ $service->serviceName }}</option>

                                        @endforeach
                                        @endif
                                        <!-- Options will be appended dynamically via jQuery -->
                                    </select>
                                </div>
                                 <!-- Status Select (Increased width to 5/12) -->
                                 {{-- sub service --}}
                                 <div class="w-full md:w-[32%]  subService_id ">
                                     <label for="subService_id" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Sub Services</label>
                                     <select name="subService_id" id="subService_id" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                         <option value="">Select Sub Service</option>
                                         @if(isset($serviceParam))
                                         @php
                                            $lead_ids = Lead::where('client_id', $leadParam)
                                            ->pluck('id');
                                            $leadSubServices = LeadTask::whereIn('lead_id', $lead_ids  )->pluck('subservice_id')->unique();
                                            $filterdSubservices = SubService::where('serviceid', $serviceParam)->whereIn('id', $leadSubServices)->get();
                                        @endphp
                                            @foreach ($filterdSubservices as $subservice)
                                            <option value="{{ $subservice->id }}" {{ $subServiceParam == $subservice->id ? 'selected' : '' }}>{{ $subservice->subServiceName }}</option>
                                            @endforeach
                                            @endif
                                            
                                        </select>
                                    </div>
                                    {{-- aplied for --}}
                                    <div class="w-full md:w-[32%]  applied_for_id ">
                                        <label for="applied_for" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Applied For</label>
                                        <select name="applied_for" id="applied_for" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                            <option value="">Applied For</option>
                                            @if(isset($subServiceParam))
                                            @php
                                                $lead_ids = Lead::where('client_id', $leadParam)
                                                ->pluck('id');
                                                $service_detail_id = LeadTask::whereIn('lead_id', $lead_ids  )->pluck('service_detail_id')->unique();
                                                $appliedFor = ServiceDetail::wherein('id', $service_detail_id)->where('service_id' , $serviceParam)->where('sub_service_id' ,$subServiceParam )->get();
                                            @endphp
                                        @foreach ($appliedFor as $applied_for)
                                        <option value="{{ $applied_for->id }}" {{ $serviceDetailsParam == $applied_for->id ? 'selected' : '' }}>{{ $applied_for->applied_for }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="w-full md:w-[32%]">
                                   <label for="status" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Status</label>
                                   <select name="status" id="status" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                       <option value="10" selected>Select Status</option>
                                       <option value="0" @if($statusParam==0) selected @endif>Pending</option>
                                       <option value="1" @if($statusParam==1) selected @endif>Completed</option>
                                       <option value="2" @if($statusParam==2) selected @endif>On Hold</option>
                                       <option value="3" @if($statusParam==3) selected @endif>Follow Up</option>
                                       <option value="4" @if($statusParam==5) selected @endif>Pending Payment</option>
                                       <option value="4" @if($statusParam==6) selected @endif>Registered</option>
                                       <option value="4" @if($statusParam==4) selected @endif>Rejected</option>
                                       
                                   </select>
                               </div>
                                <!-- User Select (visible only for roles 1 or 4, increased width to 5/12) -->
                                @if (Auth::user()->role == 1 || Auth::user()->role == 4)
                                <div class="w-full md:w-[32%]  user_id">
                                    <label for="user" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">User</label>
                                    <select name="user" id="user" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                        @if(!$users->isEmpty())
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if($user->id == $userParam) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @endif
                        <div class="w-full flex justify-end gap-[15px] mt-[10px]">
                            <button id="filterButton" class="text-[14px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[12px] py-[16px] px-[35px]">
                                Filter
                            </button>
                            <a id="resetButton" href="{{ route('task.index') }}" class="  text-[14px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[12px] py-[16px] px-[35px]">
                                Reset
                            </a>
                        </div>
                        
                        </div>
                    </div>
                </form>
             
            </div>
           
        </div>
    </div>
    <div class="mt-4">
        <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden ">
            <div class=" mb-4 flex items-center justify-end gap-[15px] pt-[20px] pr-[20px]">
                <div class="switchOffBx">
                    <label class="toggle">
                        <span class="onoff">Filter Hide</span>
                        <input type="checkbox" id="filterToggleSwitch"/>
                        <span class="slider round"></span>
                      </label>
                </div>
                <div class="relative w-full md:w-[217px] mt-[10px] md:mt-0">
                    <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                    </svg>
                    <input type="search" name="search" id="search" placeholder="Search" class="search !outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px]">
                </div>
            </div>
            <div class="overflow-x-auto " id="search_table_data">
                    <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[900px]">
                    <thead>
                        <tr>
                            <th class="text-start w-[120px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                                SR.NO
                            </th>
                           
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Clients Name
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Task Detail
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Assigned User
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Service
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Applied For
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Service Type	
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Deadline
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                status
                            </th>
                            <th class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                       
                        @if(!$taskDetails->isEmpty())
                        {{-- @dd(auth()->user()->role == 1) --}}
                        @foreach ($taskDetails as $task)
                        <tr>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                                
                                {{ $loop->index + 1 }}
                                
                            </td>
                            
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if(!empty($task->lead->client_name))
                                {{ $task->lead->client_name }}
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if ($task->serviceSatge)
                                {{ $task->serviceSatge->description }}
                                @php
                                $stageId = $task->serviceSatge->id;
                                @endphp
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if( $task->user)
                                {{ $task->user->name }}
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if( $task->services)
                                {{ $task->services->serviceName }}
                                @php
                                $serviceID = $task->services->id;
                                @endphp
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if( $task->serviceDetails)
                                    {{ $task->serviceDetails->applied_for }}                                
                                @else
                                    Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if( $task->subService)
                                {{ $task->subService->subServiceName }}
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if($task->leadTaskDetails && $task->leadTaskDetails->dead_line)
                                {{ \Carbon\Carbon::parse($task->leadTaskDetails->dead_line)->format('d M Y') }}
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if($task->leadTaskDetails && !is_null($task->leadTaskDetails->status))
                                    @php
                                        $statusMap = [
                                            0 => ['label' => 'Pending', 'color' => 'bg-yellow-100 text-yellow-800'],
                                            1 => ['label' => 'Completed', 'color' => 'bg-green-100 text-green-800'],
                                            2 => ['label' => 'Hold', 'color' => 'bg-orange-100 text-orange-800'],
                                            3 => ['label' => 'Follow-up', 'color' => 'bg-blue-100 text-blue-800'],
                                            4 => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800'],
                                            5 => ['label' => 'Payment', 'color' => 'bg-purple-100 text-purple-800'],
                                            6 => ['label' => 'Registered', 'color' => 'bg-teal-100 text-teal-800'],
                                        ];
                                        $status = $statusMap[$task->leadTaskDetails->status] ?? ['label' => 'Other', 'color' => 'bg-gray-100 text-gray-700'];
                                    @endphp
                                    <span class="{{ $status['color'] }} inline-block text-center min-w-[100px] py-[5px] px-[10px] rounded-[5px]">
                                        {{ $status['label'] }}
                                    </span>
                                @else
                                    Not Available
                                @endif
                            </td>
                            <td class="text-center border-b-[1px] border-[#0000001A] py-[12px] px-[15px]">
                                @if((in_array('task.followup',$permissionDetails['accessableRoutes']) || in_array('leadLogs.index',$permissionDetails['accessableRoutes']) || in_array('task.hold',$permissionDetails['accessableRoutes'])) || auth()->user()->role==1)
                                <div class="dropdown inline-block relative ml-[auto] mr-[20px] ">
                                    <a href="javascript:void(0)" type="button" class="button flex items-center justify-center bg-[#13103a] px-[12px] py-[15px] rounded-[5px] text-[#fff]">
                                        <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <div class="dropdown_menus absolute right-0 z-10 mt-2 w-[100px] origin-top-right rounded-md bg-white shadow-md ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                        <div class="text-start" role="none">
                                            @if(in_array('task.followup', $permissionDetails['accessableRoutes']) || auth()->user()->role == 1)
                                            @if(!empty($serviceID) && !empty($stageId))
                                           
                                            @if($task->leadTaskDetails->status != 1 && $task->leadTaskDetails->status != 4)
                                            <a href="{{ route('task.followup', ['id' => $task->id, 'serviceId' => $serviceID, 'stageId' => $stageId]) }}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">
                                                Follow Up
                                            </a>
                                            @endif
                                            @endif
                                            @endif

                                            @if(!empty($task->lead->id))
                                            @php
                                            $leadId = $task->lead->id;
                                            @endphp

                                            @endif
                                            @if(in_array('leadLogs.index',$permissionDetails['accessableRoutes']) || auth()->user()->role == 1)
                                            <a href="{{route('leadLogs.index', ['lead_id' => $leadId])}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Logs</a>
                                            @endif
                                            @if(!in_array($task->leadTaskDetails->status, [1, 2, 4, 6]))
                                            @if(in_array('task.hold',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                            @if($task->leadTaskDetails->status != 1 && $task->leadTaskDetails->status != 4)
                                            <a href="#" class="hold-on-pop block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700" data-taskId="{{$task->leadTaskDetails->task_id }}" data-modal-target="assignUserModal" data-modal-toggle="assignUserModal">Hold</a>
                                            @endif
                                            @endif

                                            @php
                                                $currentParams = request()->all(); // get all existing query params
                                                $updatedParams = array_merge($currentParams, ['status' => 5]); // override status only
                                            @endphp 

                                             @if(in_array('task.index',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                                @if($task->leadTaskDetails->status != 1 && $task->leadTaskDetails->status != 4)
                                                    <a href="{{ route('task.index', $updatedParams) }}" 
                                                    class="hold-on-pop block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">
                                                    Follow Up Status
                                                    </a>
                                                @endif
                                            @endif 


                                            @endif
                                            @if(in_array('task.reject',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                            @if($task->leadTaskDetails->status != 1 && $task->leadTaskDetails->status != 4)
                                            <button class="reject-on-pop block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700" data-taskId="{{$task->leadTaskDetails->task_id }}" >Rejected</button>
                                            @endif
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                @else
                                <x-nopermission />
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8" class="text-center text-red-500 py-[12px]">No task found</td>
                        </tr>
                        @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<div id="assignUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[100%)] max-h-full bg-[rgba(0,0,0,0.6)] ">
    <div class="relative p-4 w-full max-w-[780px] max-h-full m-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-[20px] shadow">

            <div class="flex items-center justify-between p-4 md:px-5 md:py-[20px] border-b border-[#f2f2f2]">
                <h3 class="flex items-center gap-[8px] text-[24px] font-[600] leading-[17px] text-[#000]">
                    Hold Task<p id="rowLeadId" class="text-sky-500"></p>
                </h3>
                <button type="button" class=" absolute top-[-10px] right-[-10px] w-[35px] h-[35px] bg-[#13103A] flex items-center justify-center text-[#fff] rounded-[60px]" data-modal-hide="assignUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-[20px]">
                @foreach ($taskDetails as $task)
                    
                @endforeach
                <form method="POST" action="{{ route('task.hold') }}" class="space-y-[20px]">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-[20px]">
                        <div class="w-full md:w-1/2" id="verifiedDate">
                            <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                Hold On
                            </label>
                            <input type="hidden" name="task_hidden_id" id="task_hidden_id">
                            
                            <div class="w-full">
                                <div class="w-full relative">
                                    <input
                                        type="text"
                                        placeholder="Hold On"
                                        name="verified"
                                        class="daterangepicker-verified w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
                                        value=""
                                        id="verified"
                                        autocomplete="off">
                                    <div class="absolute right-[10px] top-[10px]">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-1/2" id="verifiedDate">
                            <label for="followUp" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                Reminder Date
                            </label>                        
                            <div class="w-full">
                                <div class="w-full relative">
                                    <input
                                        type="text"
                                        placeholder="Follow-up date"
                                        name="followUp"
                                        class="daterangepicker-verified w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none"
                                        value=""
                                        id="followUp"
                                        autocomplete="off">
                                    <div class="absolute right-[10px] top-[10px]">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-1/2">
                        <label for="comment" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                            Comment 
                            <span style="font-size: 12px; color: gray; font-style: italic;">(Ensure your comments are short and to the point.)</span>
                        </label>
                        <input type="text" name="comment" id="comment" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                    </div>

                    <div class="">
                        <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
                        <textarea type="text" name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-[15px]">
                        <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[10px] px-[30px]">Hold Task</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

<script>
    $(document).on('click', '.hold-on-pop', function() {
        var task_id = $(this).attr('data-taskId');
        $("#task_hidden_id").val(task_id);
        $('.daterangepicker-verified').attr("placeholder", "DD/MM/YYYY"); // Set placeholder

    $('.daterangepicker-verified').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,  // Prevent auto-update of input with selected date
        opens: 'right',
        locale: {
            format: 'DD MMM YYYY'
        },
        minDate: null,
        maxDate: moment().endOf('day'),
    }).on('apply.daterangepicker', function(ev, picker) {
        // Set the selected date format when a date is picked
        $(this).val(picker.startDate.format('DD MMM YYYY')); 
        console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
    });

// Ensure no date is selected by default (it only shows placeholder)
$('.daterangepicker-verified').val('');
    });
   
    $(document).on('click','.reject-on-pop',function(){
        let taskId = $(this).data('taskid');
        if(confirm('Are you want to sure to reject this task ?')){
           $.ajax({
                method:'POST',
                url:"{{route('task.reject')}}",
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                data:{
                    taskId:taskId
                },
                success: function(res){
                    if(res){
                        window.location.reload();
                    }
                },
                error:function(err){
                }

           })
        }
    })
    $(document).on('keyup', '.search', function() {
        var key = $(this).val();
        var formData = $("#filterForm").serialize()
        formData += '&key=' + encodeURIComponent(key);

        $.ajax({
            url: "{{ route('task.index', ['request_type' => 'ajax'] )}}",
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: formData,
            dataType: 'json',
            success: function(res) {
                $('#search_table_data').html(res.trData);
            }
        })

        $("#resetButton").on('click', function(event) {

            event.preventDefault();
            $("#filterForm")[0].reset();
            const url = new URL(window.location.href);
            url.search = '';
            window.history.replaceState({}, document.title, url.toString());
        });
    });
    $(document).ready(function() {
        $('.daterangepicker-verified').daterangepicker({
            singleDatePicker: true,
            opens: 'right',
            locale: {
                format: 'DD MMM YYYY'
            },
            minDate: moment().startOf('day'),
        }).on('apply.daterangepicker', function(ev, picker) {
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
        });
        $(".unhold").on('click' , function(){
            var id = $(this).data('unholdid')
            $.ajax({
                url: "{{route('task.unHoldTask')}}",
                method: "POST",
                data: {
                    id:id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  
                },
                success: function (response){
                         if(response.status == 200){
                            window.location.reload();
                         }
                }
            })
        })
        
            // if ($('#service_id').val()) {
            //     $('.service_id').removeClass('hidden');
            // }
            // if ($('#subService_id').val()) {
            //     $('.subService_id').removeClass('hidden');
            // }
            // if ($('#applied_for').val()) {
            //     $('.applied_for_id').removeClass('hidden');
            // }
            // $('#filterButton').on('click', function (e) {
            //     // If any section is hidden, show them and prevent form submission
            //     if ($('.service_id').hasClass('hidden') ||
            //         $('.subService_id').hasClass('hidden') ||
            //         $('.applied_for_id').hasClass('hidden')) {

            //         $('.service_id, .subService_id, .applied_for_id').removeClass('hidden');
            //         e.preventDefault(); // stop form from submitting
            //     }
            //     // Else, all visible → allow normal submission
            // });
            $('#filterToggleSwitch').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.filterToglle').removeClass('hidden');
                } else {
                    $('.filterToglle').addClass('hidden');
                }
            });

        $("#leadId").on('change' , function (){
            var client_id = $(this).val(); // here this lead id is not exact lead id this is client id nomenclature is wrong here
            // $(".service_id").removeClass('hidden')
            $.ajax({
                url: "{{ route('task.getServiceAcctoLead')  }}",
                method: "POST",
                data: {client_id: client_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  
                },
                success: function (response){
                    $('#service_id').empty().append('<option value="">Select Service</option>');
                    
                    if (response.status === 200 && response.serviceName.length > 0) {
                        
                        response.serviceName.forEach(function (service) {
                            $('#service_id').append(`<option value="${service.id}">${service.serviceName}</option>`);
                        });
                       
                    }
                         
                }
            })
        })

        $("#service_id").on('change' , function (){
            var service_id = $(this).val();
            var client_id = $('#leadId').val();  

            
            $.ajax({
                url: "{{ route('task.getSubServiceAccToService')  }}",
                method: "POST",
                data: {service_id: service_id,
                    client_id:client_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                success: function (response){
                    $('#subService_id').empty().append('<option value="">Select Sub Service</option>');
                            
                    if (response.status == 200 && response.serviceName.length > 0) {
                        
                        
                        response.serviceName.forEach(function (service) {
                            $('#subService_id').append(`<option value="${service.id}">${service.subServiceName}</option>`);
                        });
                       
                    } 
                }
            })
        })
        $("#subService_id").on('change' , function (){
            var service_id = $("#service_id").val();
            var client_id = $('#leadId').val(); 
            var subService_id = $(this).val();
            // $(".applied_for_id").removeClass('hidden')

            

            $.ajax({
                url: "{{ route('task.getAppliedFor')  }}",
                method: "POST",
                data: {service_id: service_id,
                    client_id:client_id,
                    subService_id:subService_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                success: function (response){
                    $('#applied_for').empty().append('<option value="">Select Sub Service</option>');
                            
                    if (response.status == 200 && response.serviceName.length > 0) {
                        
                        
                        response.serviceName.forEach(function (service) {
                            $('#applied_for').append(`<option value="${service.id}">${service.applied_for}</option>`);
                        });
                        
                    } 
                    
                   
                }
            })
        })

        

    })
</script>
@stop