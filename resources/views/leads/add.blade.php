@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}</h3>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">
        <form method="POST" class="py-[25px] px-[30px] space-y-[20px]">

            <input type="hidden" name="role" id="role" value="2">
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="source" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source</label>
                    <select name="source" id="source" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value="">Source Type</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="clientname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                    <input type="text" name="clientname" id="clientname" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                </div>
            </div>
            <div>
                <div class="mb-[10px] flex items-end justify-between">
                    <label class="text-[14px] font-[400] leading-[16px] text-[#000000] mb-0">Services</label>
                    <button class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Add More</button>
                </div>
                <div class="border border-[#0000001A] p-[15px] rounded-[10px]">
                    <!-- repeater start -->
                    <div class="flex flex-col md:flex-row gap-[20px]">
                        <div class="w-full md:w-1/2">
                            <select name="source" id="source" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                                <option value="">Service Name</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2">
                            <select name="source" id="source" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                                <option value="">Service Type</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                </div>
                <div class="w-full md:w-1/2">
                    <label for="mobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile number</label>
                    <input type="text" name="mobilenumber" id="mobilenumber" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                    <input type="text" name="email" id="email" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                </div>
                <div class="w-full md:w-1/2">
                    <label for="assign" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign to</label>
                    <select name="assign" id="assign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                        <option value="">Assign To Users</option>
                        <option value="1">1</option>
                    </select>
                </div>
            </div>
            <div class="">
                <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
                <textarea type="text" name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>
            </div>
            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>

@stop