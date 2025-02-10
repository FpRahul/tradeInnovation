@php
    namespace App;
    use App\Models\Service;
@endphp
@extends('layouts.default')
@section('content')
<div>
    <div class="flex items-center justify-between mb-[20px]">
        <div>
            <h3 class="text-[18px] md:text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Manage Leads</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>Leads</li> 
                <li class="text-gray">Manage Leads</li>
            </ul>
        </div>
        @if (in_array('leads.add',$permissionDetails['accessableRoutes']) || auth()->user()->role == 1)
            <a href="{{ route('leads.add')}}" class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.75 5.625H5.625V8.75C5.625 9.09375 5.34375 9.375 5 9.375C4.65625 9.375 4.375 9.09375 4.375 8.75V5.625H1.25C0.90625 5.625 0.625 5.34375 0.625 5C0.625 4.65625 0.90625 4.375 1.25 4.375H4.375V1.25C4.375 0.90625 4.65625 0.625 5 0.625C5.34375 0.625 5.625 0.90625 5.625 1.25V4.375H8.75C9.09375 4.375 9.375 4.65625 9.375 5C9.375 5.34375 9.09375 5.625 8.75 5.625Z" fill="white" />
                </svg>
                Add Lead
            </a> 
        @endif
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden ">
        <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] gap-[10px] flex flex-col md:flex-row items-end justify-between">
            <div class="flex max-w-[800px] gap-[10px] w-full">
                <form class="w-full flex flex-wrap lg:flex-nowrap gap-[10px]"  method="GET">
                    <input name="tab" type="hidden" class="leadtabbing" value="{{$allRequestData->tab}}">
                    <div class="w-[100%] md:w-[40%]">
                        <label>Source</label>
                        <select name="source"  class="sourceData allform-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] ">
                            <option value="">Select Source</option>
                            @if (!empty($sourceList))
                                @foreach ($sourceList as $sourceVal)                        
                                    <option value="{{ $sourceVal->id}}" {{ !empty($sourceKey) && $sourceVal->id == $sourceKey ? 'selected':''}}>{{ $sourceVal->name}}</option>
                                @endforeach                      
                            @endif                    
                        </select>
                    </div>
                    <div class="w-[100%] md:w-[40%]">
                        <label>Service</label>
                        <select name="service"  class="serviceData allform-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[98px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] ">
                            <option value="">Select Service</option>
    
                            @if (!empty($serviceList))
                            
                                @foreach ($serviceList as $serviceKey => $serviceListVal)       
                                           
                                    {{-- <option value="{{ $serviceListVal->id}}" {{ !empty($serviceKey) && $serviceListVal->id == $serviceKey ? 'selected':''}}>{{ $serviceListVal->serviceName}}</option> --}}
                                @endforeach                      
                            @endif  
                        </select> 
                    </div>
                    <div class="w-[100%] md:w-[40%]">
                        <label>Status</label>
                        <select name="status"  class="statusData allform-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[90px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] " >
                            <option value="">Select Status</option>
                            <option value="0" {{ isset($statusKey) && $statusKey == 0 ? 'selected':''}}>Open</option>
                        </select>    
                    </div>  
                    <div class="flex items-end gap-[10px]">
                        <button type="submit" class="lead_filter_btn text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">Filter</button>
                        <a href="{{ route('leads.index')}}" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">
                            Reset
                        </a>
                    </div>
                </form>         
            </div>
            <div class="relative w-full md:w-[217px]">
                <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                </svg>
                <input value="{{$searchKey}}" type="search" name="search" id="search" placeholder="Search By Client Name" class="!outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px] ">
            </div>
        </div>
            {{-- TAbbing --}}
        <div class="bg-white px-[20px] mb-[20px]">
            <nav class="tabs flex flex-col sm:flex-row">
                <a href="{{ route('leads.index', ['tab' => 1,'source'=>$allRequestData->source,'service'=>$allRequestData->service,'status'=>$allRequestData->status]) }}" data-target="panel-1" data-val="1" class="activeArchiveTab tab text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none text-blue-500 border-b-2 font-medium border-blue-500 {{(!isset($allRequestData->tab)) ? 'active' :( $allRequestData->tab == 1 ? 'active':'')}}">
                    Active
                </a>
                <a href="{{ route('leads.index', ['tab' => 0,'source'=>$allRequestData->source,'service'=>$allRequestData->service,'status'=>$allRequestData->status]) }}" data-target="panel-2" data-val="0" class="activeArchiveTab tab text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none {{isset($allRequestData->tab) && $allRequestData->tab == 0 ? 'active':''}}">
                    Archive
                </a>                   
            </nav>
        </div>
        <div class="overflow-x-auto px-[20px]" id="search_table_data">
            <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[900px]">
                <thead>
                    <tr>
                        <th class="text-start w-[120px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            Lead id
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Source
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Client name
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            status
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            stages
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Created
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Services
                        </th>
                        <th class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action
                        </th>
                    </tr> 
                </thead>
                <tbody>
                    
                    @if ($leadList && $leadList->isNotEmpty())
                       @foreach ($leadList as $leadKey => $leadData)   
                                        
                       <tr>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                            #{{$leadData->lead_id}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                            <span class="inline-flex items-center gap-[10px]"> 
                            @if ($leadData->source > 0 )
                                 {{ getSourceData($leadData->source)->name }}
                            @endif
                            @if ($leadData->source == 17 || $leadData->source == 18 || $leadData->source == 19)
                                <button data-tooltip-target="tooltip-default{{$leadKey}}"><img src="{{ asset('assets/images/i-icon.png') }}" alt="icon"></button>

                                <div id="tooltip-default{{$leadKey}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#13103A] rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    {{getUserNameBySource($leadData->source_id)->name}}
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            @endif
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                            {{$leadData->client_name}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">                           
                            {{$leadData->status}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">               
                          {{ isset($leadData->leadTask->task_title) ? $leadData->leadTask->task_title : 'NULL' }}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                            {{ date('d M Y H:i:A', strtotime($leadData->created_at) ) }}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                            @if (!empty($leadData->leadService))
                                {{ implode(', ', collect(getServiceData($leadData->leadService))->pluck('serviceName')->toArray()) }}                           
                            @endif                               
                        </td>
                        <td class="text-center border-b-[1px] border-[#0000001A] py-[12px] px-[15px]">
                            @if((in_array('leads.add',$permissionDetails['accessableRoutes']) || in_array('leads.logs',$permissionDetails['accessableRoutes']) || in_array('leads.archive',$permissionDetails['accessableRoutes'])) || auth()->user()->role==1)
                            <div class="dropdown inline-block relative ml-[auto] mr-[20px] ">
                                <a href="javascript:void(0)" type="button" class="button flex items-center justify-center bg-[#13103a] px-[12px] py-[15px] rounded-[5px] text-[#fff]">
                                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z" fill="currentColor" />
                                    </svg>
                                </a>
                                <div class="dropdown_menus absolute right-0 z-10 mt-2 w-[100px] origin-top-right rounded-md bg-white shadow-md ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                    <div class="text-start" role="none">
                                        @if(in_array('leads.add',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                            <a data-id="{{$leadData->id}}" class="lead_edit block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700" data-modal-target="editLeadModal" data-modal-toggle="editLeadModal" type="button">Edit</a>
                                        @endif
                                        {{-- <a href="javascript:void(0)" data-id="{{$leadData->id}}" class="lead_assign_to_user block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700" data-modal-target="assignUserModal" data-modal-toggle="assignUserModal" type="button">Assign</a> --}}
                                        @if(in_array('leads.archive',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                        <a href="#" data-id="{{$leadData->id}}" class="lead_archive block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">{{$leadData->archive ? 'Archive' : 'Active'}}</a>
                                        @endif
                                        {{-- <a href="{{ route('leads.quote')}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Send Quote</a> --}}
                                        @if(in_array('leads.logs',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)                                       
                                        <a href="{{ route('leadLogs.index', ['lead_id' => $leadData->id]) }}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Logs</a>
                                        @endif
                                        {{-- <a href="#" class="block px-3 py-1 text-[12px] hover:bg-[#f7f7f7] text-gray-700">Delete</a> --}}
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
                        <tr> <td colspan="7" class="text-center"><div class="text-[#e11010] py-[20px]">No Record Found!</div></td></tr>
                    @endif                  
                   
                </tbody>
            </table>
        </div>
        <div id="dynamic-pagination" class="py-[15px] px-[20px]">
            {{ $leadList->appends(['key' => $searchKey,'source'=>$sourceKey,'service'=>$serviceKey,'status'=>$statusKey])->links() }}
        </div>
    </div>
</div>

<!-- Main modal -->
<div id="editLeadModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[100%)] max-h-full bg-[rgba(0,0,0,0.6)] ">
    <div class="relative p-4 w-full max-w-[780px] max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-[20px] shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:px-5 md:py-[20px] border-b border-[#0000001A] rounded-t dark:border-gray-600">
                <h3 class="text-[14px] font-[400] leading-[17px] text-[#000000] dark:text-white">
                    Update Lead
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="editLeadModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-[20px]">
                <form method="POST" action="{{ route('leads.edit')}}" enctype="multipart/form-data" class="space-y-[20px]">
                    @csrf
                    <input type="hidden" name="lead_id" id="modal_lead_id" value="">
                    <div class="flex flex-col md:flex-row gap-[20px]">
                        <div class="w-full md:w-1/2">
                            <label for="modalclientname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                            <input type="text" name="modalclientname" id="modalclientname" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        </div>
                        <div class="w-full md:w-1/2">
                            <label for="modalcompanyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                            <input type="text" name="modalcompanyname" id="modalcompanyname" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        </div>
                        
                    </div>
                    <div class="flex flex-col md:flex-row gap-[20px]">  
                        <div class="w-full md:w-1/2">
                            <label for="modalmobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile number</label>
                            <input type="text" name="modalmobilenumber" id="modalmobilenumber" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        </div>                      
                        <div class="w-full md:w-1/2">
                            <label for="modalemail" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                            <input type="text" name="modalemail" id="modalemail" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Attachments <span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg png and pdf format supported & max:2 MB)</span></label>
                        <div class="leadAttachmentRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                           <div id="existedAttachment">

                           </div>
                           <input name="modalfileattachment" type="file" multiple/>
                        </div>
                    </div>
                    <div class="">
                        <label for="modaldescription" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
                        <textarea type="text" name="modaldescription" id="modaldescription" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-[15px]">
                        <button data-modal-hide="editLeadModal" type="button" class="rounded-[10px] py-[12px] px-[30px] text-[13px] font-[500] leading-[15px] tracking-[0.01em] text-gray-900 focus:outline-none bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).on('click', '.lead_edit', function () {
        let id = $(this).data('id');
        $.ajax({
            method: 'POST',
            url: '{{ route("leads.fetch") }}', 
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: { id: id },
            success: function (res) {
                $("#modal_lead_id").val(res.data.id);
                $("#modalclientname").val(res.data.client_name);
                $("#modalmobilenumber").val(res.data.mobile_number);
                $("#modalcompanyname").val(res.data.company_name);
                $("#modalemail").val(res.data.email);
                $("#modaldescription").text(res.data.description);
                let attachData = $('#existedAttachment'); // Ensure tbody is targeted if using a table
                let attachments = res.data.lead_attachments;
                attachments.forEach((ele, index) => {
                    let row = `
                        <tr>
                            <td>${ele.document}</td>
                            <td>
                                <button class="modalAttachmentDelete delete-btn" data-id="${ele.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                    attachData.append(row);
                });
            }
        })
    });

    $(document).on('click','.modalAttachmentDelete',function(){
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this element?')) {
            $.ajax({
            method: 'POST',
            url: "{{ route('lead.deleterepeater') }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                id: id
            },
            success: function(res) {
                if (res == 1) {
                }
            },
            error: function(err) {
                alert(err);
            }
        })
        }
       
    })

    $(document).on('click', '.lead_archive', function () {
        var id = $(this).data('id'); 
        if (confirm('Are you sure you want to archive this lead?')) {
            window.location.href = `{{ route('leads.archive') }}/${id}`;
        }
    });

    $(document).ready(function() {
        $('.daterangepicker-deadline').daterangepicker({
            singleDatePicker: true, 
            opens: 'right',
            locale: {
                format: 'DD MMM YYYY' 
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
        });
    });   
  
    $(document).on('keyup','#search',function(){
        let val = $(this).val();
        let source = $(this).parent().parent().find('.sourceData').val();
        let service = $(this).parent().parent().find('.serviceData').val();
        let status = $(this).parent().parent().find('.statusData').val();
        let tab = $(this).parent().parent().find('.leadtabbing').val();

        $.ajax({
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token()}}'
            },
            url:`{{ route('leads.index')}}?source=${source}&service=${service}&status=${status}&key=${val}&tab=${tab}&requestType=ajax`,
            success:function(res){
                $('#search_table_data').html(res.trData);
            }
           
        })
    });
   

</script>
@stop