<div class="flex justify-between items-center py-[15px] pr-[22px] ">
    <h3 class="text-[20px] font-[600] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$header_title_name}}</h3>
    <div class="dropdown relative ml-[auto] mr-[20px] ">
        <div type="button" class="button flex items-center gap-[10px] shadow-[0px_0px_6px_2px_#00000036] rounded-[10px] p-[3px] cursor-pointer	">
            <img src="{{asset('assets/images/profile-img.png')}}" alt="profile" class="w-[33px] h-[33px] rounded-[9px] object-cover border-[1px] border-[#13103A] ">
            <div class="pr-[7px]">
                <strong class="flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#13103A] tracking-[0.01em] capitalize mb-[2px]">
                    Hi,rajodiya infotech!
                    <svg width="9" height="5" viewBox="0 0 9 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.94 1.41753e-05L8.64667 0.707347L4.79533 4.56001C4.73362 4.62212 4.66024 4.6714 4.5794 4.70504C4.49857 4.73867 4.41188 4.75598 4.32433 4.75598C4.23678 4.75598 4.1501 4.73867 4.06926 4.70504C3.98843 4.6714 3.91505 4.62212 3.85333 4.56001L1.76971e-07 0.707347L0.706667 0.000680478L4.32333 3.61668L7.94 1.41753e-05Z" fill="#13103A" />
                    </svg>
                </strong>
                <span class="block text-[10px] font-[500] leading-[12px] text-[#5F5F66] tracking-[0.01em]">Admin</span>
            </div>
        </div>
        <div class="dropdown_menu absolute top-[100%] right-0 z-10 mt-2 w-[200px] origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <!-- Active: "bg-gray-100 text-gray-900 outline-none", Not Active: "text-gray-700" -->
                <a href="{{ route('user.myprofile')}}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">My Profile</a>
                <a href="{{ route('user.logout')}}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Logout</a>
            </div>
        </div>
    </div>
    <div class="dropdown relative flex">
        <a href="javascript:void(0)" type="button" class="button py-[12px] px-[13px] shadow-[0px_0px_6px_2px_#00000036] rounded-[10px] inline-block ">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.25 5C11.25 4.00544 10.8549 3.05161 10.1517 2.34835C9.44839 1.64509 8.49456 1.25 7.5 1.25C6.50544 1.25 5.55161 1.64509 4.84835 2.34835C4.14509 3.05161 3.75 4.00544 3.75 5C3.75 9.375 1.875 10.625 1.875 10.625H13.125C13.125 10.625 11.25 9.375 11.25 5Z" stroke="#13103A" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8.58047 13.125C8.47059 13.3144 8.31287 13.4717 8.12311 13.581C7.93335 13.6903 7.71821 13.7478 7.49922 13.7478C7.28023 13.7478 7.06509 13.6903 6.87533 13.581C6.68557 13.4717 6.52785 13.3144 6.41797 13.125" stroke="#13103A" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
        <div class="dropdown_menu absolute top-[100%] mt-2 right-0 z-10 w-[350px] origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="p-3" role="none">
                <ul>
                    <li class="py-3 border-b-[1px] border-[#e5e7eb]">
                        <div class="flex gap-[7px]">
                            <img src="{{asset('assets/images/profile-img.png')}}" alt="profile" class="min-w-[35px] w-[35px] h-[35px] rounded-[100%] object-cover border-[1px] border-[#13103A] ">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-black mb-[5px]">John cena</h4>
                                    <div class="text-xs text-gray-900">07 Jan 2025</div>
                                </div>
                                <p class="text-xs text-gray-900 line-clamp-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum.</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-3 border-b-[1px] border-[#e5e7eb]">
                        <div class="flex gap-[7px]">
                            <img src="{{asset('assets/images/profile-img.png')}}" alt="profile" class="min-w-[35px] w-[35px] h-[35px] rounded-[100%] object-cover border-[1px] border-[#13103A] ">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-black mb-[5px]">John cena</h4>
                                    <div class="text-xs text-gray-900">07 Jan 2025</div>
                                </div>
                                <p class="text-xs text-gray-900 line-clamp-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum.</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-3 ">
                        <div class="flex gap-[7px]">
                            <img src="{{asset('assets/images/profile-img.png')}}" alt="profile" class="min-w-[35px] w-[35px] h-[35px] rounded-[100%] object-cover border-[1px] border-[#13103A] ">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-black mb-[5px]">John cena</h4>
                                    <div class="text-xs text-gray-900">07 Jan 2025</div>
                                </div>
                                <p class="text-xs text-gray-900 line-clamp-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum.</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>