<div class="">
    <img src="{{asset('assets/images/logo.png')}}" alt="logo" class="mx-auto">
</div>
<div>
    <ul class="space-y-[12px] accordian ">
        @foreach ($serializeMenus as $menuKey => $menuValue)
            @if (!isset($menuValue['subMenu']))
                @if(in_array(Route::currentRouteName(),explode(',',$menuValue['menu']['groupedRoutes'])))
                    @php $activeMen = 'active'; @endphp
                @else
                    @php $activeMen = ''; @endphp
                @endif
                <li class="group {{ $activeMen }} ">
                    <a href="{{ route($menuValue['menu']['url']) }}" 
                        class="text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                            group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px]">
                        <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff]">
                            <img src="{{ asset('assets/images/' . $menuValue['menu']['icon']) }}.png" alt="icon" class="hidden group-[.active]:block">
                            <img src="{{ asset('assets/images/' . $menuValue['menu']['icon']) }}-w.png" alt="icon" class="block group-[.active]:hidden">
                        </span>
                        {{ $menuValue['menu']['name'] }}
                    </a>
                </li> 
            @elseif(isset($menuValue['subMenu']))
                @php $activeSubMenu = ''; 
                    $activeSubMenuAction = 'hidden';
                @endphp
                @if(in_array(Route::currentRouteName(),$menuSubMenuRoutes[$menuKey]))
                    @php $activeSubMenu = 'active'; 
                        $activeSubMenuAction = 'block';
                    @endphp
                @endif
                <li class="item group has-submenu {{ $activeSubMenu }}">
                    <a href="javascript:void(0);"
                        class="button nav-link flex items-center justify-between text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] 
                            group-[.active]:bg-[#13103A] group-[.active]:text-[#ffffff] group-[.active]:shadow-[0px_0px_6px_2px_#00000036] p-[3px] rounded-[10px]">
                        <span class="flex items-center gap-[10px] ">
                            <span class="w-[34px] h-[34px] rounded-[10px] inline-flex items-center justify-center bg-[#13103A] group-[.active]:bg-[#ffffff]">
                                <img src="{{ asset('assets/images/' . $menuValue['menu']['icon']) }}.png" alt="icon" class="hidden group-[.active]:block">
                                <img src="{{ asset('assets/images/' . $menuValue['menu']['icon']) }}-w.png" alt="icon" class="block group-[.active]:hidden">
                            </span>
                            {{ $menuValue['menu']['name'] }}
                        </span>
                        <svg class="inline-block group-[.active]:hidden mr-[5px]" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.451987 1.57999L1.51299 0.519991L7.29199 6.29699C7.38514 6.38956 7.45907 6.49963 7.50952 6.62088C7.55997 6.74213 7.58594 6.87216 7.58594 7.00349C7.58594 7.13482 7.55997 7.26485 7.50952 7.3861C7.45907 7.50735 7.38514 7.61742 7.29199 7.70999L1.51299 13.49L0.452987 12.43L5.87699 7.00499L0.451987 1.57999Z" fill="#000000" />
                        </svg>
                        <svg class="hidden group-[.active]:inline-block mr-[5px]" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.451987 1.57999L1.51299 0.519991L7.29199 6.29699C7.38514 6.38956 7.45907 6.49963 7.50952 6.62088C7.55997 6.74213 7.58594 6.87216 7.58594 7.00349C7.58594 7.13482 7.55997 7.26485 7.50952 7.3861C7.45907 7.50735 7.38514 7.61742 7.29199 7.70999L1.51299 13.49L0.452987 12.43L5.87699 7.00499L0.451987 1.57999Z" fill="#ffffff" />
                        </svg>
                    </a>
                    <ul class="accordian_body pl-[15px] mt-[15px] {{$activeSubMenuAction}}">
                        @foreach ($menuValue['subMenu'] as $subKey => $subMenu)
                            @if(!isset($menuValue['subSubMenu'][$subKey]))
                                <li class="group {{ $subMenu['url'] == Route::currentRouteName() ? 'active group-[.active]:bg-[#13103a0d] rounded-[8px]':'' }} ">
                                    <a href="{{ route($subMenu['url']) }}" class="p-[10px] text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] group-[.activeSubMenu]:bg-[#13103A] group-[.activeSubMenu]:text-[#ffffff] group-[.activeSubMenu]:shadow-[0px_0px_6px_2px_#00000036] rounded-[10px] ">{{ $subMenu['name'] }}</a>
                                </li>
                            @else
                                @php $activeSubSubMenu = ''; 
                                    $activeSubSubMenuAction = 'hidden';
                                @endphp
                                @if(in_array(Route::currentRouteName(),$menuSubMenuRoutes[$menuKey][$subKey]))
                                    @php $activeSubSubMenu = 'open'; 
                                        $activeSubSubMenuAction = 'block';
                                    @endphp
                                @endif
                                <li class="item group has-sub-submenu {{ $activeSubSubMenu }}">
                                    <a href="javascript:void(0);"
                                        class="button nav-link flex items-center justify-between text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] p-[10px] 
                                            group-[.open]:bg-[#13103a0d] rounded-[10px]">
                                        <span class="flex items-center gap-[10px] ">
                                            {{ $subMenu['name'] }} 
                                        </span>
                                        <svg class="inline-block mr-[5px]" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.451987 1.57999L1.51299 0.519991L7.29199 6.29699C7.38514 6.38956 7.45907 6.49963 7.50952 6.62088C7.55997 6.74213 7.58594 6.87216 7.58594 7.00349C7.58594 7.13482 7.55997 7.26485 7.50952 7.3861C7.45907 7.50735 7.38514 7.61742 7.29199 7.70999L1.51299 13.49L0.452987 12.43L5.87699 7.00499L0.451987 1.57999Z" fill="#000000" />
                                        </svg>
                                    </a>
                                    <ul class="accordian_body pl-[15px] mt-[5px] {{$activeSubMenuAction}}">
                                        @foreach ($menuValue['subSubMenu'][$subKey] as $subSubMenu)
                                            <li class="group {{ $subSubMenu['url'] == Route::currentRouteName() ? 'active group-[.active]:bg-[#13103a0d] rounded-[8px]':'' }} ">
                                            <a href="{{ route($subSubMenu['url']) }}"
                                                class="p-[10px] text-[#13103A] font-[Inter] text-[14px] font-[400] leading-[16px] flex items-center gap-[10px] 
                                                        group-[.activeSubMenu]:bg-[#13103A] group-[.activeSubMenu]:text-[#ffffff] group-[.activeSubMenu]:shadow-[0px_0px_6px_2px_#00000036] rounded-[10px] ">
                                                {{ $subSubMenu['name'] }}
                                            </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
</div>