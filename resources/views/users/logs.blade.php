@extends('layouts.default')
@section('content')
@php use App\Models\User;@endphp

<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-[20px] gap-[15px]">
        <div>
            <h3 class="text-[20px] font-[500] leading-[24px] text-[#13103A] tracking-[0.02em] mb-1.5">Activities</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>Panel Settings</li> / <li class="text-gray">Logs</li>
            </ul>
        </div>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden">
        <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] gap-[10px] flex flex-col md:flex-row items-center justify-between">
            @if(!isset($isAutoId) || !$isAutoId)
            <form id="filterForm" class="w-full" method="get">
                <div class="flex items-end gap-[10px] w-full">
                    <div class="w-[16%] relative">
                        <label class="flex text-[15px] text-[#000] mb-[5px]">Date Range</label>
                        <div class="w-[100%] relative">
                            <input type="text" placeholder="Start Date" name="dateRange" class="daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" value="">
                            {{-- <i class="ri-calendar-line absolute right-[8px] top-[9px]"></i> --}}
                        </div>
                    </div>

                    <div class="flex gap-[5px] w-[30%]">

                        <div class="w-[50%]">
                            <label class="flex text-[15px] text-[#000] mb-[5px]">User</label>
                            <select name="user_id" class="allform-filter-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[98px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] ">
                                <option value="">Select User</option>
                                @if($activityUsers->isNotEmpty())
                                @foreach($activityUsers as $user)
                                @php
                                // Try to find the user, if not found set $userName to 'N/A'
                                $userModel = User::find($user);
                                $userName = $userModel ? $userModel->name : 'N/A'; // Default value if user is not found
                                @endphp
                                <option value="{{ $user }}" {{ $filterOptions['user_id'] == $user ? 'selected' : '' }}>
                                    {{ $userName }}
                                </option>
                                @endforeach
                                @else
                                <option>No users available</option>
                                @endif


                            </select>
                        </div>

                        <div class="w-[50%]">
                            <label class="flex text-[15px] text-[#000] mb-[5px]">Activity</label>
                            <select name="activity" class="allform-filter-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] ">
                                <option value="">Select Activity</option>
                                @foreach($activityTitles as $title)
                                <option value="{{ $title }}" {{ $filterOptions['activity'] == $title ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class=" text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">Filter</button>
                    <a href="{{ route('logs.index')}}" id="resetButton" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">
                        Reset
                    </a>
                </div>
            </form>
            @endif
        </div>
        <div class="overflow-x-auto " id="search_table_data">
            <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[600px]">
                <thead>
                    <tr>
                        <th class="text-start w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            User
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Activity
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Details
                        </th>
                        <!-- ip -->
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            IP
                        </th>
                        <!-- User Agent -->
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            User Agent
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action Date
                        </th>
                    </tr>
                </thead>
                <tbody id="get_dynamic_data">
                    @if(!isset($isAutoId) || !$isAutoId)
                    @if ($systemLogs && $systemLogs->count() > 0)
                    @foreach ($systemLogs as $logs)
                    <tr>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                            {{$logs->user->name ?? 'User Not Available'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$logs->title ?? 'No Title'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$logs->description ?? 'No Description'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$logs->ip_address ?? 'Not Available'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$logs->operating_system ?? 'Not Available'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{ date('d M Y', strtotime($logs->created_at)) }}
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center text-red-500">No Logs Found!</td>
                    </tr>
                    @endif
                    @elseif($isAutoId && $query)
                    <tr>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                            {{$query->user->name ?? 'User Not Available'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$query->title ?? 'No Title'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$query->description ?? 'No Description'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$query->ip_address ?? 'Not Available'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$query->operating_system ?? 'Not Available'}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{ date('d M Y', strtotime($query->created_at)) }}
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="6" class="text-center text-red-500">No Logs Found!</td>
                    </tr>
                    @endif
                </tbody>

            </table>
            <!-- Pagination Links -->
        </div>
        @if(!isset($isAutoId) || !$isAutoId)
        <div id="dynamic-pagination" class="py-[15px] px-[20px]">
            {{ $systemLogs->appends(['key' => ''])->links() }}
        </div>
        @endif
    </div>
</div>
<script>
    $(document).ready(function() {
        // Calculate the default date range (last 7 days to today)
        var startDate = moment().subtract(7, 'days');
        var endDate = moment();

        $('.daterangepicker-item').daterangepicker({
            opens: 'right',
            startDate: startDate,
            endDate: endDate,
            locale: {
                format: 'DD MMM YYYY'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

    });
</script>
@stop