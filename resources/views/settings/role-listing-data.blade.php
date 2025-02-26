<?php 
use App\Models\Menu;
?>

<table  width="100%" cellpadding="0" cellspacing="0" class="min-w-[700px]">
                <thead>
                    <tr>
                        <th class="text-start w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            Role name
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Permissions
                        </th>
                        <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="roleTable" >
                @if($allRoles->isNotEmpty())
                    @foreach($allRoles as $role)
                    
                    <tr>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                            {{ $role->name }}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start py-[12px] px-[15px]">
                            <div class="flex gap-[8px]">
                            @if($role->roleMenus->isNotEmpty())
                                @foreach ($role->roleMenus as $menuName )
                                    @php
                                        $MenuDetail = Menu::find($menuName->menuId);
                                    @endphp
                                    @if(!empty($MenuDetail))
                                    <span class="text-[14px] font-[400] leading-[16px] text-[#ffffff] capitalize bg-[#13103A] rounded-[10px] px-[15px] py-[7px] ">
                                        {{ $MenuDetail->menuName }}
                                    </span>
                                    @endif
                                @endforeach
                            @endif
                            </div>
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] py-[12px] px-[15px] text-center">
                            <a href="{{ route('settings.addrole',['id'=>$role->id]) }}" class="m-auto flex items-center justify-center bg-[#13103A] w-[27px] h-[27px] rounded-[100%] text-center border-none p-0 ">
                                <svg class="mx-auto" width="12" height="12" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_455_4936)">
                                        <path d="M6.89951 2.69995H2.69951C2.38125 2.69995 2.07603 2.82638 1.85098 3.05142C1.62594 3.27647 1.49951 3.58169 1.49951 3.89995V12.3C1.49951 12.6182 1.62594 12.9234 1.85098 13.1485C2.07603 13.3735 2.38125 13.5 2.69951 13.5H11.0995C11.4178 13.5 11.723 13.3735 11.948 13.1485C12.1731 12.9234 12.2995 12.6182 12.2995 12.3V8.09995" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.3996 1.80004C11.6383 1.56134 11.962 1.42725 12.2996 1.42725C12.6372 1.42725 12.9609 1.56134 13.1996 1.80004C13.4383 2.03873 13.5724 2.36247 13.5724 2.70004C13.5724 3.0376 13.4383 3.36134 13.1996 3.60004L7.49961 9.30004L5.09961 9.90004L5.69961 7.50004L11.3996 1.80004Z" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_455_4936">
                                            <rect width="14.4" height="14.4" fill="white" transform="translate(0.299805 0.300049)" />
                                        </clipPath>
                                    </defs>
                                </svg> 
                            </a>
                            
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="3" class="text-center border-b-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">No Record Found</td></tr>
                @endif
                </tbody>

                <tbody id="appendData" class="hidden" >
                
                </tbody>
            </table>
           