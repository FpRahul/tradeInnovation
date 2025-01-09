@extends('layouts.login')
@section('content')



<div class="py-[30px] w-full h-[100vh] bg-cover bg-center bg-no-repeat" style="background-image: url('assets/images/login-bg.jpg');">
    <div class="max-w-[1200px] mx-auto h-full flex flex-col items-center justify-between">
        <div class="max-w-[446px] mx-auto bg-[#FFFFFF] rounded-[20px] shadow-[0px_20px_80px_0px_#0000001A] py-[24px] px-[47px] my-auto">
            <img src="{{asset('assets/images/logo.png')}}" alt="logo" class="mb-[23px] mx-auto ">
            
            <h2 class="font-[Inter] text-[20px] font-[500] text-[#13103A] leading-[24px] tracking-[-0.02em] mb-2 ">Forgot Password</h2>
            <p class="font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px]">Please provide your valid registered email.</p>
           
            <form class="mt-[20px] space-y-[15px]" method="POST" action="{{ route('forgetPassword') }}">
               
                @csrf
                <div>
                    <label for="email" class="block font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px] mb-1">Email</label>
                    <input type="email" name="email" id="email" class="font-[Inter] text-[14px] font-[400] text-[#6F6F6F] leading-[16px] border-[1px] border-[#0000001A] rounded-[10px] h-[45px] px-[15px] p-[10px] w-full">
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <a href="{{ route('login')}}" class="underline font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px]">Back to login?</a>
                </div>
                
                <div>
                    <button class="font-[Inter] text-[16px] font-[500] text-[#FFFFFF] leading-[19.36px] tracking-[-0.02em] rounded-[10px] px-[10px] py-[14px] border-[1px] border-[#13103A] bg-[#13103A] hover:bg-transparent hover:text-[#13103A] shadow-[0px_0px_6px_2px_#00000036] w-full ">
                       Change Password
                    </button>
                </div>
                
            </form>
        </div>
        <p class="text-center font-[Inter] text-[16px] font-[400] text-[#FFFFFF] leading-[19px] tracking-[-0.02em] ">Copyright®2024 Company All right Reserved</p>
    </div>
</div>

@endsection