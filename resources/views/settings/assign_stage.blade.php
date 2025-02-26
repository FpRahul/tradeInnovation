@extends('layouts.default')
@section('content')

<div>
   <div class="flex items-center justify-between mb-[20px]">
      <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]"></h3>
   </div>
   <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
      <form id="assignStage" action="{{route('task.assignTask')}}" method="POST" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
         @csrf
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="icon" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assigned Task<strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="taskAssign" id="taskAssign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
               @error('taskAssign')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
            <div class="w-full md:w-1/2">
               <label for="icon" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assigned Service<strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="service" id="service" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
               @error('service')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="url" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assigned Stage<strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="stage" id="stage" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
               @error('stage')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
            <div class="w-full md:w-1/2">
               <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Url</label>
               <input type="text" name="url" id="url" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number">
               @error('url')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Route</label>
               <input type="text" name="route" id="route" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number">
               @error('action')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="">
               <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
      </form>
   </div>
</div>
<script>

</script>
@stop