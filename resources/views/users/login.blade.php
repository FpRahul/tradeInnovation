@extends('layouts.login')
@section('content')
<div class="bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('assets/images/login-bg.jpg');">
    <div class="max-w-[1200px] py-[30px] px-[15px] mx-auto w-full min-h-[100vh] flex flex-col items-center justify-between">
        <p class="text-center font-[Inter] text-[16px] font-[400] text-[#FFFFFF] leading-[19px] tracking-[-0.02em] ">Protect and Manage Your Trademarks, Patents, Design and Copyrights Seamlessly</p>
        <div class="max-w-[446px] mx-auto my-8 bg-[#FFFFFF] rounded-[20px] shadow-[0px_20px_80px_0px_#0000001A] py-[24px] px-[47px] ">
            <img src="{{asset('assets/images/logo.png')}}" alt="logo" class="mb-[23px] mx-auto ">

            <h2 class="font-[Inter] text-[20px] font-[500] text-[#13103A] leading-[24px] tracking-[-0.02em] mb-2 ">Log In</h2>
            <p class="font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px]">Secure Access to Intellectual Property Management</p>

            <form class="mt-[20px] space-y-[15px]" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label for="email" class="block font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px] mb-1">Email</label>
                    <input type="email" name="email" id="email" class="font-[Inter] text-[14px] font-[400] text-[#6F6F6F] leading-[16px] border-[1px] border-[#0000001A] rounded-[10px] h-[45px] px-[15px] p-[10px] w-full">
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px] mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="font-[Inter] text-[14px] font-[400] text-[#6F6F6F] leading-[16px] border-[1px] border-[#0000001A] rounded-[10px] h-[45px] px-[15px] p-[10px] w-full">
                        @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <a id="eyeBtn" class="absolute top-[50%] right-[15px] translate-y-[-50%] bg-transparent border-none ">
                            🔒 
                        </a>
                    </div>
                </div>
                <div>
                    <a href="{{ route('forgetPassword')}}" class="font-[Inter] text-[14px] font-[400] text-[#13103A] leading-[16px]">Forgot your Password ?</a>
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
<script>
   $('#eyeBtn').on('click', function() {
        var passwordField = $('#password');
        var eyeIcon = $(this); 
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text'); 
            eyeIcon.text('👁️'); 
        } else {
            passwordField.attr('type', 'password'); 
            eyeIcon.text('🔒'); 
        }
    });

</script>
@endsection