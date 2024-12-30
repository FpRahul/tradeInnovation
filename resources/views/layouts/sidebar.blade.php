<div class="">
    <img src="{{asset('assets/images/logo.png')}}" alt="logo" class="mx-auto">
</div>

<div>
    <ul class="space-y-[12px]">
        @php 
            foreach ($systemMenus as $menyKey => $menuValue) {
               
            }
        @endphp
        <li class="active group">
            <a href="#"
                class="text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px]">
                <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff] ">
                    <img src="{{asset('assets/images/dashboard-icon.png')}}" alt="icon" class="hidden group-[.active]:block">
                    <img src="{{asset('assets/images/dashboard-icon-w.png')}}" alt="icon" class="block group-[.active]:hidden">
                </span>
                Dashboard
            </a>
        </li>
        <!-- Other Parents -->
        <li class="group">
            <a href="{{ route('users.listing')}}"
                class="text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px] ">
                <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff] ">
                    <img src="{{asset('assets/images/user-icon.png')}}" alt="icon" class="hidden group-[.active]:block">
                    <img src="{{asset('assets/images/user-icon-w.png')}}" alt="icon" class="block group-[.active]:hidden">
                </span>
                Users
            </a>
        </li>
        <li class="group">
            <a href="{{ route('users.roles') }}"
                class="text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px]]">
                <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff] ">
                    <img src="{{asset('assets/images/roles-icon.png')}}" alt="icon" class="hidden group-[.active]:block">
                    <img src="{{asset('assets/images/roles-icon-w.png')}}" alt="icon" class="block group-[.active]:hidden">
                </span>
                Roles
            </a>
        </li>
        <li class="group">
            <a href="#"
                class="text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px]">
                <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff] ">
                    <img src="{{asset('assets/images/leads-icon.png')}}" alt="icon" class="hidden group-[.active]:block">
                    <img src="{{asset('assets/images/leads-icon-w.png')}}" alt="icon" class="block group-[.active]:hidden">
                </span>
                Leads
            </a>
        </li>
        <li class="group">
            <a href="#"
                class="text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px]">
                <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff] ">
                    <img src="{{asset('assets/images/setting-icon.png')}}" alt="icon" class="hidden group-[.active]:block">
                    <img src="{{asset('assets/images/setting-icon-w.png')}}" alt="icon" class="block group-[.active]:hidden">
                </span>
                Services
            </a>
        </li>
    </ul>
</div>