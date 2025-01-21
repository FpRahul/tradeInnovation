@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}Associate</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
        <form method="POST" action="{{ route('users.addassociate',['id'=>$newAssociate->id])}}" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
            @csrf
            <input type="hidden" name="role" id="role" value="3">
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="name" id="name" value="{{$newAssociate->name}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name" required>

                </div>
                <div class="w-full md:w-1/2">
                    <label for="profession" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Profession <strong class="text-[#f83434]">*</strong></label>
                    <select name="profession" id="profession" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        @if (count($professionDataList) > 0)
                            @foreach ($professionDataList as $professionDataListDetails)  
                                <option value="{{$professionDataListDetails->id}}">{{$professionDataListDetails->name}}</option>                      
                            @endforeach                                                            
                        @endif
                    </select>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="firmName" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Firm Name <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="firmName" id="firmName" value="{{$newAssociate->companyName}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="address" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Address <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="address" id="address" value="{{$newAssociate->address}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile Number <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="number" id="number" value="{{$newAssociate->mobile}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="alternatePhone" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Mobile Number</label>
                    <input type="text" name="alternatePhone" value="{{$newAssociate->altNumber}}" id="alternatePhone" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email">
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="email" id="email" value="{{$newAssociate->email}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="alternateEmail" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Email-Id</label>
                    <input type="text" name="alternateEmail" value="{{$newAssociate->altEmail}}" id="alternateEmail" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email">
                </div>
            </div>

            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>

@stop