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
    <form action="{{ route('task.documentVerified' , ['id' =>$taskID]) }}" method="POST" class="space-y-[20px]" enctype="multipart/form-data">
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
                    $status = 'Not Register';
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
                    <option value="1">Register</option>

                    @endif

                </select>
                @error('status')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full md:w-1/2">
                <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Verified On</label>
                @if($taskDetails->count() > 0)
                @foreach ( $taskDetails as $task )
                <input type="date" name="verified" id="verified" value="{{$task->leadTaskDetails->status_date}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                @endforeach
                @endif
            </div>
            @error('verified')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
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
                    Current user assigned: {{$user->user->name}}.
                </p>
                @endforeach
                @endif
                @error('assignUser')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            @if($taskDetails->count() > 0)
            @foreach ($taskDetails as $user)
            <input type="hidden" value="{{$user->user->id}}" name="alreadyAssign">
            @endforeach
            @endif
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
            @error('deadline')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="flex justify-start gap-[15px] w-[100%] md:w-[49%]">
                <label for="attachment" class="flex items-center gap-[10px] w-full text-[13px] font-[500] leading-[15px] text-[#666666] tracking-[0.01em] bg-[#fff] border-dashed border-[1px] border-[#ccc] rounded-[6px] py-[14px] px-[15px] cursor-pointer">
                    <svg width="22" height="28" viewBox="0 0 22 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.248 15.6125V21.9035C10.248 22.1175 10.3195 22.296 10.4625 22.439C10.6055 22.582 10.784 22.6535 10.998 22.6535C11.212 22.6535 11.3905 22.582 11.5335 22.439C11.6765 22.296 11.748 22.1175 11.748 21.9035V15.6125L14.367 18.2315C14.44 18.3045 14.522 18.3595 14.613 18.3965C14.705 18.4325 14.7965 18.4495 14.8875 18.4475C14.9785 18.4445 15.0735 18.4245 15.1725 18.3875C15.2705 18.3515 15.3555 18.2965 15.4275 18.2225C15.5875 18.0575 15.669 17.881 15.672 17.693C15.675 17.504 15.594 17.327 15.429 17.162L11.8455 13.5785C11.7155 13.4485 11.582 13.357 11.445 13.304C11.309 13.251 11.16 13.2245 10.998 13.2245C10.836 13.2245 10.687 13.251 10.551 13.304C10.415 13.357 10.2815 13.4485 10.1505 13.5785L6.56705 17.162C6.42105 17.308 6.34455 17.48 6.33755 17.678C6.33055 17.876 6.41005 18.058 6.57605 18.224C6.74105 18.383 6.91805 18.464 7.10705 18.467C7.29605 18.47 7.47255 18.389 7.63655 18.224L10.248 15.6125ZM2.92205 27.5C2.23105 27.5 1.65455 27.269 1.19255 26.807C0.730547 26.345 0.499047 25.7685 0.498047 25.0775V2.9225C0.498047 2.2325 0.729547 1.6565 1.19255 1.1945C1.65555 0.7325 2.23205 0.501 2.92205 0.5H13.7415C14.0645 0.5 14.3785 0.565 14.6835 0.695C14.9885 0.825 15.2495 0.9995 15.4665 1.2185L20.778 6.53C20.995 6.748 21.169 7.009 21.3 7.313C21.431 7.617 21.4965 7.931 21.4965 8.255V25.076C21.4965 25.766 21.265 26.3425 20.802 26.8055C20.339 27.2685 19.7635 27.5 19.0755 27.5H2.92205ZM13.998 6.788C13.998 7.137 14.113 7.426 14.343 7.655C14.573 7.884 14.862 7.999 15.21 8H19.998L13.998 2V6.788Z" fill="#13103A" />
                    </svg>
                    Upload File
                </label>
                <input type="file" id="attachment" name="attachment[]" multiple style="display: none;" />
            </div>
            @error('attachment.*')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
            <div class="w-full md:w-1/2 stageoftheservice">
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Stage</label>
                <select name="stage_id" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none serviceStagesOption" required>
                    @if($getStage->count() > 0)
                    @foreach ( $getStage as $stage )
                    <option value="{{$stage->id}}">{{ $stage->title }}</option>
                    @endforeach
                    @endif
                </select>
                @error('stage_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                 @enderror
            </div>
        </div>
        <div class="">
            <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
            @if($taskDetails->count() > 0)
            @foreach ( $taskDetails as $task )
            <textarea type="text" name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">{{$task->task_description}}</textarea>
            @endforeach
            @endif
            @error('description')
            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-end gap-[15px]">
            <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Assign</button>
        </div>
    </form>
</div>
<script>

</script>
@stop