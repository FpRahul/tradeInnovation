<div>
   @if ($notificationData && $notificationData->isNotEmpty())
       @foreach ($notificationData as $notificationDataList)
       <li class="py-3 pr-[10px] border-b-[1px] border-[#e5e7eb]">
            <a href="#" class="w-full flex gap-[7px]">
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
    <li class="py-3 border-b-[1px] border-[#e5e7eb]">No Record Found!</li>
   @endif
</div>