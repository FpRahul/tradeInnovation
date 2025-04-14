@extends('layouts.default')
@section('content')

<div>

    <div class="flex items-center justify-between mb-[20px]">
        <div>
            <h3 class="text-[18px] md:text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Manage Opposition</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>Home</li> /
                <li class="text-gray">lead</li>
            </ul>
        </div>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[50px]">
        <div class="px-[20px] md:px-[40px] py-[30px] flex flex-wrap ">
            <div class="flex items-center gap-[12px] w-full">
                <form id="filterForm" action="{{ route('lead.oppositionDetails') }}" class="w-full" method="GET">
                    <div class="flex items-end gap-[15px] w-full">
                        <div class="w-[60%] md:w-[40%]">
                            <label class="flex text-[18px] text-[#000] mb-[8px]">Lead ID<strong class="text-[#f83434]">*</strong></label>
                            <select name="lead_id" id="lead_id" class="allform-filter-select2 !outline-none h-[50px] border border-[#0000001A] w-full md:w-[120px] rounded-[12px] p-[12px] text-[16px] font-[400] leading-[18px] text-[#13103A] ">
                                <option value="">Select Lead ID</option>
                                @forelse($leadData as $leadDetails)
                                    <option value="{{ $leadDetails->id }}" 
                                        @if(isset($requestParams) && $requestParams == $leadDetails->id) selected @endif>
                                        {{ $leadDetails->lead_id }} - {{ $leadDetails->client_name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No leads available</option>
                                @endforelse
                            </select>
                        </div>
                        <button class=" text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">Filter</button>
                        <a href="{{ route('lead.oppositionDetails') }}" id="resetButton" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
     <div class="px-[15px] md:px-[30px] py-[20px] hiddenClass hidden border-t-[1px] border-[#0000001A]  ">
        {{-- Display task details if they exist --}}
        <ul class="grid grid-cols-2 lg:grid-cols-3 gap-[20px]">
            @if(!$commanData->isEmpty())
            @foreach ($commanData as $opposition_details)
            <input type="hidden" name="client_status" id="client_status" value="{{ $opposition_details->serviceDetail->client_status }}">
            <li>
                    <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Lead ID</span>
                    <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                        {{ $opposition_details->lead->lead_id ?? "N/A" }}
                    </strong>
                </li>
                <li>
                    <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Client Name</span>
                    <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                        {{ $opposition_details->lead->client_name ?? "N/A" }}
                    </strong>
                </li>
                <li>
                    <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1" id="name">Opponent Name</span>
                    <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                        {{ $opposition_details->opponent_name ?? "N/A" }}
                    </strong>
                </li>
                <li>
                    <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1" id="number">Opposition No.</span>
                    <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                        {{ $opposition_details->opposition_number ?? "N/A" }}
                    </strong>
                </li>
                <li>
                    <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1" id="date">Opposition Date</span>
                    <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize"> 
                        {{ $opposition_details->opposition_date ? \Carbon\Carbon::parse($opposition_details->opposition_date)->format('d M Y') : 'N/A' }}
                    </strong>
                </li>
                <li>
                    <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Service Type</span>
                    <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#ff3232] capitalize"> 
                        {{ $opposition_details->subServiceID->subServiceName ?? "N/A" }}
                    </strong>
                </li>
                @endforeach
                @endif
        </ul>
    </div>
</div>
    <div class="hidden hiddenClass  mt-[50px]">
        
        <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden ">
            {{-- <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] gap-[10px] flex flex-col md:flex-row items-end justify-between">

                <div class="relative w-full md:w-[217px] mt-[10px] md:mt-0">
                    <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                    </svg>
                    <input type="search" name="search" id="search" placeholder="Search" class="search !outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px]">
                </div>
            </div> --}}
            <div class="overflow-x-auto " id="search_table_data">
                <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[900px]">
                    <thead>
                        <tr>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                S.No
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Remark
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Evidence Received On
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Evidence Submitted On
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Reason
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Attachment
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$data->isEmpty())
                            @foreach ($data as $opposition_details)
                                @if ($loop->index == 0)
                                @continue
                            @endif
                                <tr>
                                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                        {{ $loop->index + 1}}
                                    </td>
                                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                        {{ $opposition_details->remark ?? "N/A" }}
                                    </td>
                                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                        {{ $opposition_details->evidence_received ? \Carbon\Carbon::parse($opposition_details->evidence_received)->format('d M Y') : 'N/A' }}

                                    </td>
                                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                        {{ $opposition_details->evidence_submit ? \Carbon\Carbon::parse($opposition_details->evidence_submit)->format('d M Y') : 'N/A' }}

                                    </td>
                                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                        {{ $opposition_details->reason ?? 'N/A'  }}
                                    </td>
                                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                        @php
                                            $attachments = json_decode($opposition_details->attachment, true); // Decode JSON to array
                                        @endphp
                                        @if(!empty($attachments) && is_array($attachments))
                                            <a href="#" class="download-all inline-block text-[#13103A]" data-lead="{{ $opposition_details->lead_id }}" data-files="{{ implode(',', $attachments) }}">
                                                <i class="downloadFile ri-download-2-line text-[22px]"></i>
                                            </a>
                                        @else
                                     N/A
                                        @endif
                                    </td>
                                    
                                    
                                    
                                    
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center text-red-500 py-[12px]">No task found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function (){
      let requestParams = "{{ $requestParams ?? '' }}"; // Convert PHP to JS variable

    if (requestParams !== '') {
        $(".hiddenClass").removeClass("hidden"); 
    } else if(requestParams == ''){
        $(".hiddenClass").addClass("hidden"); 

    }
    var client_status  = $("#client_status").val()
    if(client_status == 2 ){
        $("#name").text('Applicant Name');
        $("#number").text('Applicant No.');
        $("#date").text('Opposed On');
    }else if(client_status == 1){
        $("#name").text('Opponent Name');
        $("#number").text('Opposition No');
        $("#date").text('Opposition Date');
    }
    $(document).ready(function () {
        $('.download-all').on('click', function (event) {
            event.preventDefault();

            let leadId = $(this).data('lead');
            let files = $(this).data('files').split(',');

            $.each(files, function (index, file) {
                let link = $('<a>', {
                    href: `/uploads/leads/${leadId}/${file.trim()}`,
                    download: file.trim()
                }).appendTo('body');

                link[0].click();
                link.remove();
            });
        });
    });
  
    })
</script>
@stop