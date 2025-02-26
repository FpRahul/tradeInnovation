@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Dashboard</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[10px] md:gap-[20px] mb-[30px]">
        <div class="flex shadow-[0px_0px_14px_2px_#0000001F] bg-white border border-[#0000001A] rounded-[10px] overflow-hidden">
            <a href="{{route('services.index')}}" class="bg-[#F5F5F5] w-[76px] min-h-[64px] flex justify-center items-center">
                <img src="{{asset('assets/images/dash_icon1.png')}}" alt="icon">
            </a>
            <a href="{{route('services.index')}}" class="flex items-center justify-between w-[calc(100%-76px)] p-4">
                <h5 class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] ">Services</h5>
                <span class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] "><strong class="font-[700] text-[#13103A]">{{$activeServices> 0 ? $activeServices : 0}}</strong></span>
            </a>
        </div>
        <div class="flex shadow-[0px_0px_14px_2px_#0000001F] bg-white border border-[#0000001A] rounded-[10px] overflow-hidden">
            <a href="{{ route('leads.index') }}" class="bg-[#F5F5F5] w-[76px] min-h-[64px] flex justify-center items-center">
                <img src="{{asset('assets/images/followups.png')}}" alt="icon">
            </a>
            <a href="{{ route('leads.index') }}" class="flex items-center justify-between w-[calc(100%-76px)] p-4">
                <h5 class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] ">Leads</h5>
                <span class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] "><strong class="font-[700] text-[#13103A]">{{$activeLeads > 0 ? $activeLeads : 0}}</strong></span>
            </a>
        </div>
        <div class="flex shadow-[0px_0px_14px_2px_#0000001F] bg-white border border-[#0000001A] rounded-[10px] overflow-hidden">
            <a href="{{route('client.listing')}}" class="bg-[#F5F5F5] w-[76px] min-h-[64px] flex justify-center items-center">
                <img src="{{asset('assets/images/clients-icon.png')}}" alt="icon">
            </a>
            <a href="{{route('client.listing')}}" class="flex items-center justify-between w-[calc(100%-76px)] p-4">
                <h5 class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] ">Clients</h5>
                <span class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] "><strong class="font-[700] text-[#13103A]">{{$activeClient > 0 ? $activeClient : 0}}</strong></span>
            </a>
        </div>
        <div class="flex shadow-[0px_0px_14px_2px_#0000001F] bg-white border border-[#0000001A] rounded-[10px] overflow-hidden">
            <a href="{{route('associate.listing')}}" class="bg-[#F5F5F5] w-[76px] min-h-[64px] flex justify-center items-center">
                <img src="{{asset('assets/images/associate.png')}}" alt="icon">
            </a>
            <a href="{{route('associate.listing')}}" class="flex items-center justify-between w-[calc(100%-76px)] p-4">
                <h5 class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] ">Associates</h5>
                <span class="text-[14px] leading-[16px] text-[#6F6F6F] font-[400] "><strong class="font-[700] text-[#13103A]">{{ $activeAssociate > 0 ? $activeAssociate : 0 }}</strong></span>
            </a>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-[30px] mb-[30px]">
        @if(false)
        <div class="w-full lg:w-[75%]">
            <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[10px] md:rounded-[20px] p-0 md:p-6">
                <canvas id="roundedLineChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <div class="w-full lg:w-[25%]">
            <div class="shadow-[0px_0px_13px_5px_#0000000f] h-full bg-white rounded-[10px] md:rounded-[20px] p-3 md:p-6">
                <h4 class="text-[14px] leading-[16px] text-[#000000] font-[500] tracking-[0.04em] mb-3">Status Matrix</h4>
                <ul class="flex flex-col gap-[10px]">
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Unassigned
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Doc. Verified
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Objection
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Payment Processed
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Awaiting Payment
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Formality Checkup
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Examination
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Objected
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Rejected
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                    <li class="bg-[#F4F4F4] text-[#13103A] text-[14px] leading-[16px] font-[400] rounded-[7px] overflow-hidden pl-[10px] flex justify-between items-center ">
                        Closed
                        <span class="min-w-[30px] h-[30px] bg-[#E8E7FF] text-[#13103A] text-[14px] leading-[100%] font-[400] inline-flex justify-center items-center">3</span>
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[10px] md:rounded-[20px] overflow-hidden">
        <div class="py-[25px] px-[20px] flex items-center justify-between">
            <h4 class="text-[14px] leading-[16px] text-[#000000] font-[500] tracking-[0.04em] ">Recent Activity</h4>
        </div>
        <div class="overflow-x-auto ">
        <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[600px]">
                <thead>
                    <tr>
                        <th class="text-start whitespace-nowrap text-[12px] w-[200px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            User
                        </th>
                        <th class="text-start whitespace-nowrap text-[12px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Activity
                        </th>
                        <th class="text-start whitespace-nowrap text-[12px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Details
                        </th>
                        <!-- ip -->
                        <th class="text-start whitespace-nowrap text-[12px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            IP
                        </th>
                        <!-- User Agent -->
                        <th class="text-start whitespace-nowrap text-[12px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                        User Agent
                        </th>
                        <th class="text-start whitespace-nowrap text-[12px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action Date
                        </th>
                        <th class="text-start whitespace-nowrap text-[12px] bg-[#D9D9D933] md:text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action Date
                        </th>
                    </tr>
                </thead>
                
                <tbody id="get_dynamic_data">
                    @if ($systemLogs && $systemLogs->isNotEmpty())
                    @foreach ($systemLogs as $logs)
                    <tr>
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                            {{$logs->user->name ?? "N/A"}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$logs->title ?? "N/A"}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$logs->description ?? "N/A"}}
                        </td>
                        @if($logs->ip_address)
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                        {{$logs->ip_address ?? "N/A" }}
                        </td>
                        @else
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                        Not Available
                       </td>
                        @endif
                        @if($logs->operating_system ?? "N/A" )
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                        {{$logs->operating_system ?? "N/A" }}
                        </td>
                        @else
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                        Not Available
                        </td>
                        @endif
                        <td class="border-b-[1px] border-[#0000001A] [tr:last-child>&]:border-[transparent] text-start whitespace-nowrap text-[12px] md:text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{ date('d M Y',strtotime($logs->created_at)) ?? "N/A" }}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-center  text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                            <a href="{{ route('getActionLog.log', ['auto'=> $logs->id]) }}" class="inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_446_3019)">
                                         <path d="M0.762451 7.05005C0.762451 7.05005 3.01245 2.55005 6.94995 2.55005C10.8875 2.55005 13.1375 7.05005 13.1375 7.05005C13.1375 7.05005 10.8875 11.55 6.94995 11.55C3.01245 11.55 0.762451 7.05005 0.762451 7.05005Z" stroke="#13103A" stroke-width="1.125" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6.94995 8.73755C7.88193 8.73755 8.63745 7.98203 8.63745 7.05005C8.63745 6.11807 7.88193 5.36255 6.94995 5.36255C6.01797 5.36255 5.26245 6.11807 5.26245 7.05005C5.26245 7.98203 6.01797 8.73755 6.94995 8.73755Z" stroke="#13103A" stroke-width="1.125" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_446_3019">
                                            <rect width="13.5" height="13.5" fill="white" transform="translate(0.199951 0.300049)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center text-red-500">No Logs Found!</td>
                    </tr>
                    @endif
                </tbody>
                
            </table>
        </div>
        <div class="text-center p-[20px] border-t-[1px] border-[#0000001A]">
            <a href="{{route('logs.index')}}" class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
                View More
            </a>
        </div>
    </div>
</div>



<script>
    async function fetchChartData() {
        const response = await fetch('/chart-data');
        const data = await response.json();

        const ctx = document.getElementById('roundedLineChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: data.datasets 
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    fetchChartData();

   
</script>
@stop


 