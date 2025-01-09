@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('users.addclient',['id' => $newClient->id])}}" class="py-[25px] px-[30px] space-y-[20px]">
            @csrf
            <input type="hidden" name="role" id="role" value="3">
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name</label>
                    <input type="text" name="name" id="name" value="{{$newClient->name}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name" required>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="{{$newClient->companyName}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Company Name" required>
                    @error('companyname')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="incorporationtype" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Incorporation type</label>
                    <select name="incorporationtype" id="incorporationtype" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value="">Select Incorporation Type</option>
                        <option value="1" {{$newClientDetails->incorporationType == 1 ?'selected':''}}>Individual / Sole Proprietor</option>
                        <option value="2" {{$newClientDetails->incorporationType == 2 ?'selected':''}}>Partnership</option>
                        <option value="3" {{$newClientDetails->incorporationType == 3 ?'selected':''}}>LLP</option>
                        <option value="4" {{$newClientDetails->incorporationType == 4 ?'selected':''}}>Company</option>
                        <option value="5" {{$newClientDetails->incorporationType == 5 ?'selected':''}}>Society</option>
                        <option value="6" {{$newClientDetails->incorporationType == 6 ?'selected':''}}>Trust</option>
                        <option value="7" {{$newClientDetails->incorporationType == 7 ?'selected':''}}>HUF</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile Number</label>
                    <input type="text" name="number" id="number" value="{{$newClient->mobile}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number" required>
                    @error('number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="address" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Address </label>
                    <input type="text" name="address" id="address" value="{{$newClient->address}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                    @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="alternatePhone" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Mobile Number</label>
                    <input type="text" name="alternatePhone" id="alternatePhone" value="{{$newClient->altNumber}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Alternate mobile number">
                    @error('alternatePhone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                    <input type="text" name="email" id="email" value="{{$newClient->email}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email" required>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="alternateEmail" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Email-Id</label>
                    <input type="text" name="alternateEmail" id="alternateEmail" value="{{$newClient->altEmail}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Alternate Email">
                    @error('alternateEmail')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="communi_address" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Communication address</label>
                    <input type="text" name="communi_address" id="communi_address" value="{{$newClient->communicationAdress}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                    @error('communi_address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="referralPartner" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Referral Partner</label>
                    <select name="referralPartner" id="referralPartner" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value=""  >Select Referral Partner</option>
                        <option value="1" {{$newClientDetails->referralPartner == 1 ?'selected':''}}>Associate</option>
                        <option value="2" {{$newClientDetails->referralPartner == 2 ?'selected':''}}>Employee</option>
                        <option value="3" {{$newClientDetails->referralPartner == 3 ?'selected':''}}>Goggle</option>
                        <option value="4" {{$newClientDetails->referralPartner == 4 ?'selected':''}}>Fackbook</option>
                        <option value="5" {{$newClientDetails->referralPartner == 5 ?'selected':''}}>Newspaper</option>
                        <option value="6" {{$newClientDetails->referralPartner == 6 ?'selected':''}}>Client Referral</option>
                    </select>
                </div>
            </div>
            <div class="">
                <label for="referralPartner" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Registered under Startup scheme</label>
                <div class="flex flex-wrap gap-[20px]">
                    <div>
                        <input type="radio" name="registered" id="registered" value='1' {{$newClientDetails->registered == 1 ?'checked':''}} >
                        <label for="registered" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="registered" id="registered2" value='0' {{$newClientDetails->registered == 0 ?'checked':''}} >
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

@stop