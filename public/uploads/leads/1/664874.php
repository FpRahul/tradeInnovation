@extends('layouts.default')
@section('content')
<div>
    <div class="flex items-center justify-between mb-[20px]">
        <div>
            <h3 class="text-[20px] font-[500] leading-[24px] text-[#13103A] tracking-[0.02em] mb-1">Quote</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>Leads</li> /
                <li class="text-gray">Quote</li>
            </ul>
        </div>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">
        <form method="POST" class="py-[25px] px-[30px] space-y-[20px]">

            <input type="hidden" name="role" id="role" value="2">
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email</label>
                    <input type="text" name="email" id="email" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                </div>
                <div class="w-full md:w-1/2">
                    <label for="subject" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Subject</label>
                    <input type="text" name="subject" id="subject" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                </div>
            </div>
            <div class="">
                <label for="message" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Message</label>
                <div id="summernote"></div>
            </div>
            <div>
                <label for="attachfile" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Attach file</label>
                <input type="file" name="attachfile" id="attachfile" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
            </div>
            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Send Quote</button>
            </div>
        </form>
    </div>
</div>
@stop