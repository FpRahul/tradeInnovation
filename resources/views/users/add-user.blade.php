@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Create Users</h3>
    </div>
    
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('users.adduser', ['id' => $newUser->id]) }}" class="py-[25px] px-[30px] space-y-[20px]">
            @csrf

            <input type="hidden" name="role" id="role" value="2">
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name</label>
                    <input type="text" name="name" id="name" value="{{ $newUser->name }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name">
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="{{ $newUser->companyName }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Company Name">
                    @error('companyname')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="incorporationtype" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Incorporation type</label>
                    <select name="incorporationtype" id="incorporationtype" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value="" {{ $newUserDetails->incorporationType == '' ? 'selected' : '' }}>Select Incorporation Type</option>
                            <option value="1" {{ $newUserDetails->incorporationType == '1' ? 'selected' : '' }}>Individual / Sole Proprietor</option>
                            <option value="2" {{ $newUserDetails->incorporationType == '2' ? 'selected' : '' }}>Partnership</option>
                            <option value="3" {{ $newUserDetails->incorporationType == '3' ? 'selected' : '' }}>LLP</option>
                            <option value="4" {{ $newUserDetails->incorporationType == '4' ? 'selected' : '' }}>Company</option>
                            <option value="5" {{ $newUserDetails->incorporationType == '5' ? 'selected' : '' }}>Society</option>
                            <option value="6" {{ $newUserDetails->incorporationType == '6' ? 'selected' : '' }}>Trust</option>
                            <option value="7" {{ $newUserDetails->incorporationType == '7' ? 'selected' : '' }}>HUF</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Number</label>
                    <input type="text" name="number" id="number" value="{{ $newUser->mobile }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number">
                    @error('number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
               
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email</label>
                    <input type="text" name="email" id="email" value="{{ $newUser->email }}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email">
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="referralpartner" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Referral Partner</label>
                    <select name="referralpartner" id="referralpartner" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value=""  {{ $newUserDetails->referralPartner == '' ? 'selected':''}}>Select Referral Partner</option>
                        <option value="1" {{ $newUserDetails->referralPartner == '1' ? 'selected':''}}>Associate</option>
                        <option value="2" {{ $newUserDetails->referralPartner == '2' ? 'selected':''}}>Employee</option>
                        <option value="3" {{ $newUserDetails->referralPartner == '3' ? 'selected':''}}>Goggle</option>
                        <option value="4" {{ $newUserDetails->referralPartner == '4' ? 'selected':''}}>Fackbook</option>
                        <option value="5" {{ $newUserDetails->referralPartner == '5' ? 'selected':''}}>Newspaper</option>
                        <option value="6" {{ $newUserDetails->referralPartner == '6' ? 'selected':''}}>Client Referral</option>
                    </select>
                </div>
            </div>
            <div class="">
                <label for="address" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Address</label>
                <textarea type="text" name="address" id="address" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Address">{{ $newUser->address }}</textarea>
                @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="">
                <label for="registered" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Registered under Startup scheme</label>
                <div class="flex flex-wrap gap-[20px]">
                    <div>
                        <input type="radio" name="registered" id="registered" checked>
                        <label for="registered" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="registered" id="registered2">
                        <label for="registered2" class="text-[12px] font-[400] leading-[14px] text-[#000000]">No</label>
                    </div>
                </div>
            </div>
            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection