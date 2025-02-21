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
            {{ $taskDetails->serviceSatge->description ?? "Title not found" }} 
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
           
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Client Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->client_name ?? 'N/A' }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Company Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->company_name ?? 'N/A' }}
                </strong>
            </li>
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
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Mobile Number</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->mobile_number ?? 'N/A' }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Inbound Date</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                {{ \Carbon\Carbon::parse($taskDetails->leadTaskDetails->dead_line)->format('d M Y') ?? 'N/A' }}
                </strong>
            </li>
          
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Referral</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->lead->referral->name ?? 'N/A' }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Assigned User</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $taskDetails->user->name ?? 'N/A' }}
                </strong>
            </li>
        @else
            <li>No task details found</li>
        @endif
    </ul>
</div>
