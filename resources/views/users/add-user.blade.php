@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Create Users</h3>
    </div>
    
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('users.adduser')}}">
            @csrf

            <input type="hidden" name="role" id="role" value="3" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Name">
            <div class="py-[25px] px-[20px]">
                <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name</label>
                <input type="text" name="name" id="name" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Name">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>
            <div class="py-[25px] px-[20px]">
                <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                <input type="text" name="companyname" id="companyname" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Company Name">
                @error('companyname')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="py-[25px] px-[20px]">
                <label for="incorporationtype" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Incorporation type</label>
                <select name="incorporationtype" id="incorporationtype" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" >
                    <option value="">Select Incorporation Type</option>
                    <option value="1">Individual / Sole Proprietor</option>
                    <option value="2">Partnership</option>
                    <option value="3">LLP</option>
                    <option value="4">Company</option>
                    <option value="5">Society</option>
                    <option value="6">Trust</option>
                    <option value="7">HUF</option>
                </select>
            </div>
            <div class="py-[25px] px-[20px]">
                <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Number</label>
                <input type="text" name="number" id="number" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Number">
                @error('number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="py-[25px] px-[20px]">
                <label for="registered" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Registered under Startup scheme </label>
                <input type="text" name="registered" id="registered" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Registered under Startup scheme">
                @error('registered')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="py-[25px] px-[20px]">
                <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email</label>
                <input type="text" name="email" id="email" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Email">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="py-[25px] px-[20px]">
                <label for="address" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Address</label>
                <textarea type="text" name="address" id="address" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Address"></textarea>
                @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="py-[25px] px-[20px]">
                <label for="referralpartner" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Referral Partner</label>
                <select name="referralpartner" id="referralpartner" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" >
                    <option value="">Select Referral Partner</option>
                    <option value="1">Associate</option>
                    <option value="2">Employee</option>
                    <option value="3">Goggle</option>
                    <option value="4">Fackbook</option>
                    <option value="5">Newspaper</option>
                    <option value="6">Client Referral</option>
                </select>
            </div>
            <div class="py-[25px] px-[20px]">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection