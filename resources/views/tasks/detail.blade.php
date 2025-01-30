@extends('layouts.default')
@section('content')
<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
    <div class="px-[15px] md:px-[30px] py-[20px] border-b-[1px] border-[#0000001A] flex flex-wrap items-center justify-between ">
        <div class="flex items-center gap-[8px]">
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 7H3.83L9.42 1.41L8 0L0 8L8 16L9.41 14.59L3.83 9H16V7Z" fill="black" />
                </svg>
            </a>
            <h3 class="text-[17px] leading-[26px] font-[600] tracking-[-0.03em] ">Client Information</h3>
        </div>
        <p class="text-[14px] leading-[16px] font-[600] text-[#2C2C2C] ">Action summary on Rahul Chouhan (lead <span class="text-[#2196F3]">#001</span>)</p>
    </div>
    <div class="px-[15px] md:px-[30px] py-[20px]">
        <ul class="grid grid-cols-2 lg:grid-cols-3 gap-[20px]">
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Client name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Rahul Chouhan</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Company Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Futureprofilez</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Services</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Trademark</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Mobile Number</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">123456789</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Inbound Date</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">23 Dec 2024</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Associate</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Surya</strong>
            </li>
        </ul>
    </div>
</div>

<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white px-[15px] md:px-[30px] py-[20px] rounded-[20px] mt-[20px] overflow-hidden ">
    <form method="POST" class="space-y-[20px]">

        <input type="hidden" name="role" id="role" value="2">
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
                <label for="status" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Status</label>
                <select name="status" id="status" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                    <option value="1">Completed</option>
                    <option value="0">Incomplete</option>
                </select>
            </div>
            <div class="w-full md:w-1/2">
                <label for="verified" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Verified On</label>
                <input type="date" name="verified" id="verified" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
                <label for="assignUser" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign User</label>
                <select name="assignUser" id="assignUser" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                    <option value="1">Assign User</option>
                    <option value="0">User</option>
                </select>
            </div>
            <div class="w-full md:w-1/2">
                <label for="deadline" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Deadline</label>
                <input type="date" name="deadline" id="deadline" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
            </div>
        </div>
        <div class="">
            <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
            <textarea type="text" name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"></textarea>
        </div>
        <div class="flex justify-end gap-[15px]">
            <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Assign</button>
        </div>
    </form>
</div>

@stop