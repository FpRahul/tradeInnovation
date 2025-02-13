<div class="px-[15px] md:px-[30px] py-[20px] border-b-[1px] border-[#0000001A] flex flex-wrap items-center justify-between">
    <div class="flex items-center gap-[8px]">
        <a href="#">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 7H3.83L9.42 1.41L8 0L0 8L8 16L9.41 14.59L3.83 9H16V7Z" fill="black" />
            </svg>
        </a>
        <h3 class="text-[17px] leading-[26px] font-[600] tracking-[-0.03em]">Client Information</h3>
    </div>

    @if($taskDetails)
        <!-- Display Task Title and Client Info -->
        <p class="text-[14px] leading-[16px] font-[600] text-[#2C2C2C]">
            {{ $taskDetails->serviceSatge->title ?? "Title not fiund" }} 
            (lead <span class="text-[#2196F3]">#{{ $taskDetails->lead->lead_id ?? 'N/A' }}</span>)
        </p>
    @else
        <p>No task details found</p>
    @endif
</div>
<div class="px-[15px] md:px-[30px] py-[20px]">
    {{-- Display task details if they exist --}}
    <ul class="grid grid-cols-2 lg:grid-cols-3 gap-[20px]">
        @if($taskDetails)
            <!-- Display Client Name -->
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Client Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->client_name ?? 'N/A' }}
                </strong>
            </li>

            <!-- Display Company Name -->
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Company Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->company_name ?? 'N/A' }}
                </strong>
            </li>

            <!-- Display Services -->
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Services</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                @if( $taskDetails->services)
                              {{ $taskDetails->services->serviceName }}
                             
                            
                            @else
                            Not Available
                            @endif
                </strong>
            </li>

            <!-- Display Mobile Number -->
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Mobile Number</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->mobile_number ?? 'N/A' }}
                </strong>
            </li>

            <!-- Display Inbound Date -->
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Inbound Date</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->leadTaskDetails->dead_line ?? 'N/A' }}
                </strong>
            </li>
            <!-- Display Associate -->
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Associate</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    N/A
                </strong>
            </li>
        @else
            <!-- If no task details are found -->
            <li>No task details found</li>
        @endif
    </ul>
</div>
