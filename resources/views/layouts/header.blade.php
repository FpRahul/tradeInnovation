<?php
    use App\Models\Role;
    use App\Models\User;
    use App\Models\UserDetail;

?>
<div class="bg-white rounded-[10px] flex justify-between items-center py-[15px] px-[15px] xl:px-[22px] ">
    <button id="toggle_btn" class="bg-transparent border-none p-0 inline-block xl:hidden mr-4">
        <svg class="w-[28px]" width="32" height="24" viewBox="0 0 32 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M31.5 12C31.5 12.3978 31.342 12.7794 31.0607 13.0607C30.7794 13.342 30.3978 13.5 30 13.5H2C1.60217 13.5 1.22064 13.342 0.939339 13.0607C0.658035 12.7794 0.5 12.3978 0.5 12C0.5 11.6022 0.658035 11.2206 0.939339 10.9393C1.22064 10.658 1.60217 10.5 2 10.5H30C30.3978 10.5 30.7794 10.658 31.0607 10.9393C31.342 11.2206 31.5 11.6022 31.5 12ZM23.5 22C23.5 22.3978 23.342 22.7794 23.0607 23.0607C22.7794 23.342 22.3978 23.5 22 23.5H2C1.60217 23.5 1.22064 23.342 0.939339 23.0607C0.658035 22.7794 0.5 22.3978 0.5 22C0.5 21.6022 0.658035 21.2206 0.939339 20.9393C1.22064 20.658 1.60217 20.5 2 20.5H22C22.3978 20.5 22.7794 20.658 23.0607 20.9393C23.342 21.2206 23.5 21.6022 23.5 22ZM15.5 2C15.5 2.39782 15.342 2.77936 15.0607 3.06066C14.7794 3.34196 14.3978 3.5 14 3.5H2C1.60217 3.5 1.22064 3.34196 0.939339 3.06066C0.658035 2.77936 0.5 2.39783 0.5 2C0.5 1.60217 0.658035 1.22064 0.939339 0.93934C1.22064 0.658036 1.60217 0.5 2 0.5H14C14.3978 0.5 14.7794 0.658035 15.0607 0.93934C15.342 1.22065 15.5 1.60218 15.5 2Z" fill="#13103A" stroke="black" />
        </svg>
    </button>
    <h3 class="hidden xl:block text-[20px] font-[600] leading-[24px] text-[#13103A] tracking-[0.02em] capitalize">{{$header_title_name}}</h3>
    <div class="dropdown relative ml-[auto] mr-[10px] md:mr-[20px] ">
        <div type="button" class="button flex items-center gap-[10px] shadow-[0px_0px_6px_2px_#00000036] rounded-[10px] p-[3px] cursor-pointer	">
            @php
                $userDetails = UserDetail::where('userId',auth()->user()->id)->first();
                if(!empty($userDetails)){
                    $profileImage = $userDetails['uploadPhotograph'];
                }else{
                    $profileImage = '';
                }
            @endphp
            <img src="{{asset($profileImage ? 'uploads/users/'.auth()->user()->id.'/'.$profileImage : 'assets/images/noimage.png')}}"            
             alt="profile" class="w-[33px] h-[33px] rounded-[9px] object-cover border-[1px] border-[#13103A] ">
            <div class="pr-[7px]">
                <strong class="flex items-center gap-[10px] text-xs md:text-[13px] font-[500] leading-[15px] text-[#13103A] tracking-[0.01em] capitalize mb-[2px]">
                    Hi, @php echo auth()->user()->name; @endphp
                    <svg width="9" height="5" viewBox="0 0 9 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.94 1.41753e-05L8.64667 0.707347L4.79533 4.56001C4.73362 4.62212 4.66024 4.6714 4.5794 4.70504C4.49857 4.73867 4.41188 4.75598 4.32433 4.75598C4.23678 4.75598 4.1501 4.73867 4.06926 4.70504C3.98843 4.6714 3.91505 4.62212 3.85333 4.56001L1.76971e-07 0.707347L0.706667 0.000680478L4.32333 3.61668L7.94 1.41753e-05Z" fill="#13103A" />
                    </svg>
                </strong>
                <span class="block text-[10px] font-[500] leading-[12px] text-[#5F5F66] tracking-[0.01em]">
                    @php
                        $role = Role::find(auth()->user()->role);
                        echo $role->name;
                    @endphp                   
                </span>
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
    <x-GetAssignedNotification />  
</div>