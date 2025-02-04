
<div class="dropdown relative flex"><div class="countNoti">{{$notifyCount}}</div>
<a href="javascript:void(0)" type="button" class="button py-[12px] px-[13px] shadow-[0px_0px_6px_2px_#00000036] rounded-[10px] inline-block ">
    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11.25 5C11.25 4.00544 10.8549 3.05161 10.1517 2.34835C9.44839 1.64509 8.49456 1.25 7.5 1.25C6.50544 1.25 5.55161 1.64509 4.84835 2.34835C4.14509 3.05161 3.75 4.00544 3.75 5C3.75 9.375 1.875 10.625 1.875 10.625H13.125C13.125 10.625 11.25 9.375 11.25 5Z" stroke="#13103A" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M8.58047 13.125C8.47059 13.3144 8.31287 13.4717 8.12311 13.581C7.93335 13.6903 7.71821 13.7478 7.49922 13.7478C7.28023 13.7478 7.06509 13.6903 6.87533 13.581C6.68557 13.4717 6.52785 13.3144 6.41797 13.125" stroke="#13103A" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
</a>
<div class="dropdown_menu absolute top-[100%] mt-2 right-0 z-10 w-[300px] md:w-[350px] origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
    <div class="p-3" role="none">
        <ul class="notifList">
            <div>                
                @if ($notificationData && $notificationData->isNotEmpty())
                    @foreach ($notificationData as $notificationDataList)       
                        <li class="py-3 pr-[10px] border-b-[1px] border-[#e5e7eb]">
                            @if ($notificationDataList->title == 'Lead Assigned' )
                            <a href="{{route('leads.index',['id'=>base64_encode($notificationDataList->lead_id),'NotifyId'=>base64_encode($notificationDataList->id)])}}" class="w-full flex gap-[7px]">
                            @elseif ($notificationDataList->title == 'Task Assigned')
                            <a href="{{route('task.index',['id'=>base64_encode($notificationDataList->task_id),'NotifyId'=>base64_encode($notificationDataList->id)])}}" class="w-full flex gap-[7px]">
                            @endif
                                <img src="{{asset('assets/images/profile-img.png')}}" alt="profile" class="min-w-[35px] w-[35px] h-[35px] rounded-[100%] object-cover border-[1px] border-[#13103A] ">
                                <div class="w-full flex flex-col">                        
                                    <div class="w-full flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-black mb-[5px]">{{ $notificationDataList->title}}</h4>
                                        <div class="text-xs text-gray-900">{{ date('d M Y',strtotime($notificationDataList->created_at))}}</div>
                                    </div>
                                    <p class="text-xs text-gray-900 line-clamp-2">{{ $notificationDataList->description}}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="py-3 border-b-[1px] border-[#e5e7eb] text-[#e70e0e] text-center">No Record Found!</li>
                @endif
            </div> 
        </ul>
    </div>
</div>
</div>