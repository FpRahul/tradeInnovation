@extends('layouts.default')
@section('content')

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
    <div>
        <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden ">
        <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] gap-[10px] flex flex-col md:flex-row items-end justify-between">
                <form action="" class="w-full flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-6/12 flex items-center gap-[20px]">
                    <div class="w-full flex items-end gap-[10px]">
                        <div class="w-full">
                            <label for="mobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Lead ID</label>
                            <input type="text" name="leadId" id="leadId"  class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter here Lead ID">
                        </div>
                        <button class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">
                            Filter
                        </button>
                        <button id="resetButton" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">
                        Reset
                    </button>
                    </div>
                </div>
                </form>
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
                                Lead id
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Clients Name
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Task Detail
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Services
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Services type
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
                        @foreach ($taskDetails as $task)
                        @if ($task->leadTaskDetails->status != 1)
                        @foreach($task->leadServices as $service)
                            
                            @endforeach
                        <tr>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                                @if(!empty($task->lead->lead_id))
                                #{{ $task->lead->lead_id }}
                                @else
                                Not Available
                                @endif
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
                                {{ $task->serviceSatge->title }}
                                @php
                                $stageId = $task->serviceSatge->id;
                                
                                @endphp
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @foreach($task->leadServices as $service)
                                @if($service->service && $service->service->serviceName)
                                {{ $service->service->serviceName }}
                                @php
                                $serviceID = $service->service->id;
                                @endphp
                                @else
                                Not Available
                                @endif
                                @endforeach
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @php
                                $subServicesname = [];
                                @endphp
                                @foreach ($task->leadServices as $leadService)
                                @if ($leadService->subservice)
                                @php
                                $subServicesname[] = $leadService->subservice->subServiceName;
                                @endphp
                                @else
                                Not Available
                                @endif
                                @endforeach
                                {{ implode(', ', $subServicesname) }}
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if($task->leadTaskDetails && $task->leadTaskDetails->dead_line)
                                {{ $task->leadTaskDetails->dead_line }}
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                @if($task->leadTaskDetails && !is_null($task->leadTaskDetails->status))
                                @php
                                // Default status is 'Other'
                                $status = 'Other';
                                // Switch based on the value of 'status'
                                switch ($task->leadTaskDetails->status) {
                                    case 0:
                                        $status = 'Pending';
                                        break;
                                        case 1:
                                            $status = 'Completed';
                                            break;
                                            case 2:
                                                $status = 'Hold';
                                                break;
                                                case 3:
                                $status = 'Follow-up';
                                break;
                            }
                            @endphp
                                <span class="text-[#13103A] bg-[#ADD8E6] inline-block text-center min-w-[100px] py-[5px] px-[10px] rounded-[5px]">
                                    {{ $status }}
                                </span>
                                @else
                                Not Available
                                @endif
                            </td>
                            <td class="text-center border-b-[1px] border-[#0000001A] py-[12px] px-[15px]">
                                <div class="dropdown inline-block relative ml-[auto] mr-[20px] ">
                                    <a href="javascript:void(0)" type="button" class="button flex items-center justify-center bg-[#13103a] px-[12px] py-[15px] rounded-[5px] text-[#fff]">
                                        <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <div class="dropdown_menus absolute right-0 z-10 mt-2 w-[100px] origin-top-right rounded-md bg-white shadow-md ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                        <div class="text-start" role="none">
                                            {{-- @php
                                                $data = getSubStage( $serviceID, $stageId);
                                                $finalStageId = $data &&  $data->stage_id ? $data->stage_id : $stageId;
                                                @endphp --}}
                                                @if(optional($task->leadTaskDetails)->status == 1)
                                                <p></p>
                                                @elseif(!empty($serviceID) && !empty($stageId))
                                                <a href="{{ route('task.followup', ['id' => $task->id,'serviceId' => $serviceID , 'stageId' => $stageId ])}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Follow Up</a>
                                                @endif
                                                @if(!empty($task->lead->id))
                                                @php
                                                $leadId = $task->lead->id;
                                                @endphp
                                                @else
                                                Not Available
                                                @endif
                                            <a href="{{route('leadLogs.index', ['lead_id' => $leadId])}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700" data-modal-target="assignUserModal" data-modal-toggle="assignUserModal" type="button">Logs</a>
                                            <a href="#" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Hold</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
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
    <script>
        $(document).on('keyup', '.z', function() {
            var key = $(this).val();
            $.ajax({
                method: 'GET',
                url: "{{ route('task.index')}}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    key: key,
                    requestType: 'ajax',
                },
                dataType: 'json',
                success: function(res) {
                    console.log(res);
                    $('#search_table_data').html(res.trData);
                }
            })

            $("#resetButton").on('click', function() {
            event.preventDefault();
            window.history.replaceState({}, document.title, window.location.pathname);
            window.location.reload();
            $("#filterForm")[0].reset();
        });
        });
    </script>
    @stop