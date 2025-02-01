@extends('layouts.default')
@section('content')
<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
    <div class="px-[15px] md:px-[30px] py-[20px] border-b-[1px] border-[#0000001A] flex flex-wrap items-center justify-between ">
        <div class="flex items-center gap-[8px]">
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 7H3.83L9.42 1.41L8 0L0 8L8 16L9.41 14.59L3.83 9H16V7Z" fill="black" />
                </svg>
            </a>
            <h3 class="text-[17px] leading-[26px] font-[600] tracking-[-0.03em] ">Client Information</h3>
        </div>
        @if($taskDetails->count() > 0)
        @foreach ($taskDetails as $task )
        <p class="text-[14px] leading-[16px] font-[600] text-[#2C2C2C] ">Document verification {{ $task->lead->client_name ?? N/A }} (lead <span class="text-[#2196F3]">#{{$task->lead->id ?? N/A}}</span>)</p>
        @endforeach
        @endif
    </div>
    <div class="px-[15px] md:px-[30px] py-[20px]">
        {{-- resources/views/someview.blade.php --}}

        <ul class="grid grid-cols-2 lg:grid-cols-3 gap-[20px]">

            @if($taskDetails->count() > 0)
            @foreach($taskDetails as $task)
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Client name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $task->lead->client_name ?? 'N/A' }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Company Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $task->lead->company_name ?? 'N/A' }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Services</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    @php
                    $serviceNames = [];
                    @endphp
                    @foreach ($task->leadServices as $leadService)
                    @if ($leadService->service)
                    @php
                    $serviceNames[] = $leadService->service->serviceName;
                    @endphp
                    @else
                    Not Available
                    @endif
                    @endforeach
                    {{ implode(', ', $serviceNames) }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Mobile Number</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    {{ $task->lead->mobile_number ?? 'N/A' }}
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Inbound Date</span>
                {{ $task->leadTaskDetails->dead_line }}
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                </strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Associate</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize">
                    N/A
                </strong>
            </li>
            @endforeach
            @endif

        </ul>


    </div>

</div>

<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white px-[15px] md:px-[30px] py-[20px] rounded-[20px] mt-[20px] overflow-hidden ">
    <form action="{{ route('task.documentVerified' , ['id' =>$taskID]) }}" method="POST" class="space-y-[20px]">
      @csrf
        <!-- <input type="hidden" name="role" id="role" value="2"> -->
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
                <label for="status" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Status</label>
                <select name="status" id="status" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                    <option value="" disabled selected>Select status</option> 
                    @if($taskDetails->count() > 0)
                        @foreach ($taskDetails as $task)
                            @php
                                // Set the status based on the task's status
                                $status = 'other';
                                if($task->leadTaskDetails->status == 0){
                                    $status = 'Pending';
                                } elseif($task->leadTaskDetails->status == 1) {
                                    $status = 'Registered';
                                } elseif($task->leadTaskDetails->status == 2) {
                                    $status = 'Hold';
                                } elseif($task->leadTaskDetails->status == 3) {
                                    $status = 'Follow-up';
                                }
                            @endphp

                            <option value="{{ $task->leadTaskDetails->status }}" {{ old('status', $task->status) == $task->status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                        <option value="0">Not register</option>
                    @endif
                    
                </select>
            </div>
            <div class="w-full md:w-1/2">
                <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Verified On</label>
                @if($taskDetails->count() > 0)
                @foreach ( $taskDetails as $task )
                <input type="date" name="verified" id="verified" value="{{$task->leadTaskDetails->status_date}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                @endforeach
                @endif
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
                <label for="assignUser" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign User</label>
                <select name="assignUser" id="assignUser" class="filterData assignUserData allform-select2 !outline-none h-[45px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A]">
                @if($users->count() > 0)
                <option value="" disabled selected>Select a user</option>  
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ !empty($selectedUserId) && $user->id == $selectedUserId ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            @else
                <option value="" disabled>No users available</option>
            @endif

                </select>
                @if($taskDetails->count() > 0)
                @foreach ($taskDetails as $user )
                <p style="color: skyblue; font-size: 14px; font-weight: 500;">
                    Initially this task is assigned to: {{$user->user->name}}
                </p>


                @endforeach
                @endif
            </div>
            <div class="w-full md:w-1/2">
                <label for="deadline" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Deadline</label>
                @if($taskDetails->count() > 0)
                @foreach ( $taskDetails as $task )
                <input type="date" name="deadline" id="deadline" value="{{ $task->leadTaskDetails->dead_line }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                @endforeach
                @else
                <input type="date" name="deadline" id="deadline" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="No deadline set">
                @endif
            </div>
        </div>
        <div class="">
            <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
            @if($taskDetails->count() > 0)
            @foreach ( $taskDetails as $task )
            <textarea type="text" name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">{{$task->task_description}}</textarea>
            @endforeach
            @endif
        </div>
        <div class="flex justify-end gap-[15px]">
            <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Assign</button>
        </div>
    </form>
</div>

@stop