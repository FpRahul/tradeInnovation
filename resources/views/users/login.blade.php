@extends('layouts.login')
@section('content')



<div class="py-[30px] w-full h-[100vh] bg-cover bg-center bg-no-repeat" style="background-image: url('assets/images/login-bg.jpg');">
    <div class="max-w-[1200px] mx-auto h-full flex flex-col items-center justify-between">
        <p class="text-center font-[Inter] text-[16px] font-[400] text-[#FFFFFF] leading-[19px] tracking-[-0.02em] ">Protect and Manage Your Trademarks, Patents, Design and Copyrights Seamlessly</p>
        <div class="max-w-[446px] mx-auto bg-[#FFFFFF] rounded-[20px] shadow-[0px_20px_80px_0px_#0000001A] py-[24px] px-[47px] ">
            <img src="{{asset('assets/images/logo.png')}}" alt="logo" class="mb-[23px] mx-auto ">
            <h2 class="font-[Inter] text-[20px] font-[500] text-[#13103A] leading-[24px] tracking-[-0.02em] mb-2 ">Log In</h2>
            <p class="font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px]">Secure Access to Intellectual Property Management</p>

            <form class="mt-[20px] space-y-[15px]">
                <div>
                    <label for="email" class="block font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px] mb-1">Email</label>
                    <input type="email" name="email" id="email" class="font-[Inter] text-[14px] font-[400] text-[#6F6F6F] leading-[16px] border-[1px] border-[#0000001A] rounded-[10px] h-[45px] px-[15px] p-[10px] w-full">
                </div>
                <div>
                    <label for="password" class="block font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px] mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="font-[Inter] text-[14px] font-[400] text-[#6F6F6F] leading-[16px] border-[1px] border-[#0000001A] rounded-[10px] h-[45px] px-[15px] p-[10px] w-full">
                        <button id="eyeBtn" class="absolute top-[50%] right-[15px] translate-y-[-50%] bg-transparent border-none ">
                            <svg class="passEye" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.66672 1.66675L13.3334 12.3334M11.0001 10.1707C10.0981 10.6567 9.07872 11.0001 8.00005 11.0001C5.64672 11.0001 3.57739 9.36542 2.39139 8.18608C2.07805 7.87475 1.92139 7.71941 1.82205 7.41341C1.75072 7.19541 1.75072 6.80475 1.82205 6.58675C1.92205 6.28075 2.07872 6.12475 2.39205 5.81342C2.99005 5.21875 3.81205 4.50941 4.78139 3.95141M13.0001 8.75608C13.2221 8.56075 13.4254 8.36808 13.6081 8.18675L13.6101 8.18475C13.9221 7.87408 14.0787 7.71808 14.1781 7.41408C14.2494 7.19608 14.2494 6.80475 14.1781 6.58675C14.0781 6.28141 13.9221 6.12541 13.6087 5.81408C12.4221 4.63475 10.3541 3.00008 8.00005 3.00008C7.77472 3.00008 7.5525 3.0143 7.33339 3.04275M8.88205 8.00008C8.62383 8.2281 8.28737 8.34733 7.94319 8.33278C7.59901 8.31823 7.27381 8.17103 7.03576 7.92202C6.7977 7.67302 6.66526 7.34154 6.66618 6.99705C6.66711 6.65256 6.80133 6.3218 7.04072 6.07408" stroke="#6F6F6F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <a href="#" class="font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px]">Forgot your Password ?</a>
                </div>
                <div>
                    <button class="font-[Inter] text-[16px] font-[500] text-[#FFFFFF] leading-[19.36px] tracking-[-0.02em] rounded-[10px] px-[10px] py-[14px] border-[1px] border-[#13103A] bg-[#13103A] hover:bg-transparent hover:text-[#13103A] shadow-[0px_0px_6px_2px_#00000036] w-full ">
                        Log In
                    </button>
                </div>
                <div class="font-[Inter] text-[12px] font-[400] text-[#C90202] leading-[15px] text-center ">
                    Your data is securely encrypted to ensure the confidentiality of your intellectual property.
                </div>
            </form>
        </div>
        <p class="text-center font-[Inter] text-[16px] font-[400] text-[#FFFFFF] leading-[19px] tracking-[-0.02em] ">Copyright®2024 Company All right Reserved</p>
    </div>
</div>

@endsection