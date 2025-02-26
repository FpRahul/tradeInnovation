@extends('layouts.default')
@section('content')

<div>
   <div class="flex items-center justify-between mb-[20px]">
      <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]"></h3>
   </div>
   <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
      <form id="assignMenu" method="POST" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
         @csrf
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="profession" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Menu</label>
               <select name="menuName" id="menuName" class="selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" >
                  <option value="" selected>Select menu Type</option>

                  @if($data->count() > 0)
                  @foreach ($data as $details)
                  <option value="{{$details->id}}" data-rowId="{{$details->parentId}}">{{$details->menuName}}</option>
                  @endforeach
                  @else
                  <option value="">Not Available</option>
                  @endif
               </select>
            </div>
            <div class="w-full md:w-1/2">
               <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name <strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="name" id="name" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name">
               @error('name')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="icon" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Icon</label>
               <input type="text" name="icon" id="icon" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
               @error('icon')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
            <div class="w-full md:w-1/2">
               <label for="url" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">url <strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="url" id="url" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
               @error('url')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Action <strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="action" id="action" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number">
               @error('action')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="">
            <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
         </div>
      </form>
   </div>
</div>
<script>
   $(document).ready(function() {

      $("#assignMenu").on('submit', function(e) {
         var formData = $(this).serialize()
         e.preventDefault()
         $.ajax({
            url: "{{route('setting.getMenu')}}",
            method: "POST",
            data: formData,
            headers: {
               "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                      if(response.message == 'success' && response.status == 200){
                         window.location.href = "{{ route('dashboard') }}";
                      }
                      

            },
            error: function(xhr, status, error) {
               console.error("Error:", error);
            }
         })
      })
   })
</script>
@stop