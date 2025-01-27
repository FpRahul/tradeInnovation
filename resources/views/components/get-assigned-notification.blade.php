{{-- <div>
   @if ($assignedData && $assignedData->isNotEmpty())
       @foreach ($assignedData as $assignedDataList)
       <li class="py-3 border-b-[1px] border-[#e5e7eb]">
            <a href="#" class="flex gap-[7px]">
                <img src="{{asset('assets/images/profile-img.png')}}" alt="profile" class="min-w-[35px] w-[35px] h-[35px] rounded-[100%] object-cover border-[1px] border-[#13103A] ">
                <div>
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-black mb-[5px]">{{ $assignedDataList->assign_lead}}</h4>
                        <div class="text-xs text-gray-900"></div>
                    </div>
                    <p class="text-xs text-gray-900 line-clamp-2"></p>
                </div>
            </a>
        </li>
       @endforeach
    @else
    <li class="py-3 border-b-[1px] border-[#e5e7eb]">No Record Found!</li>
   @endif
</div> --}}