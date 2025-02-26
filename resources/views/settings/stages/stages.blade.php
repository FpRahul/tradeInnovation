@extends('layouts.default')
@section('content')
<div>
   <div class="flex items-center justify-between mb-[20px]">
      <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]"></h3>
   </div>
   <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
      <form id="stageAssign" action="{{route('stages.create')}}" method="POST" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
         @csrf
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="profession" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Services <strong class="text-[#f83434]">*</strong></label>
               <select name="services" id="services" class="selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">
                  @if($services->count() > 0)
                  <option value="" selected>Select services</option>
                  @foreach ( $services as $data )
                  <option value="{{$data->id}}" data-rowId="{{$data->parent_id}}">{{$data->name}}</option>
                  @endforeach
                  @else
                  <option value="" selected>Not Available</option>
                  @endif
               </select>
            </div>
            <input type="hidden" name="rowId" id="rowId">
            <div class="w-full md:w-1/2">
               <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Stage Name <strong class="text-[#f83434]">*</strong></label>
               <input type="text" name="name" id="name" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name">
               @error('name')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
         </div>
         <div class="flex flex-col md:flex-row gap-[20px]">
            <div class="w-full md:w-1/2">
               <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description <strong class="text-[#f83434]">*</strong></label>
               <textarea name="description" id="description" class="w-full h-[100px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required></textarea>
               @error('description')
               <div class="alert alert-danger">{{ $message }}</div>
               @enderror
            </div>
            <div class="w-full md:w-1/2 mt-[57px]">
               <div class="flex items-center mb-[5px]">
                  <label for="moveable" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mr-4">Moveable <strong class="text-[#f83434]">*</strong></label>
                  <input type="radio" name="moveable" id="moveable_yes" value="1" class="mr-2 transform scale-150" required>
                  <label for="moveable_yes" class="text-[14px] font-[400] leading-[16px] text-[#000000] mr-4">Yes</label>
                  <input type="radio" name="moveable" id="moveable_no" value="0" class="mr-2 transform scale-150">
                  <label for="moveable_no" class="text-[14px] font-[400] leading-[16px] text-[#000000]">No</label>
               </div>
               @error('moveable')
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

document.getElementById('services').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var rowId = selectedOption.getAttribute('data-rowId');
        document.getElementById('rowId').value = rowId;
    });
   // $(document).ready(function(){
   //    $("#stageAssign").on("submit", function(e){
   //          var servesParentID = $('#services').find('option:selected').data('rowid');
   //          var formData = $(this).serialize()
   //          formData += '&parent_id=' + servesParentID;
   //          e.preventDefault()
   //          $.ajax({
   //             url: "{{ route('stages.create') }}",
   //             method: "POST",
   //             data:formData,
   //             headers: {
   //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
   //              },
   //             success: function(response){
   //                       if(response.message == 'success' && response.status == 200 ){
   //                         window.location.href = "{{ route('dashboard') }}";
   //                       }
                         
   //             }

   //          })
   //       })



   // });
</script>
@stop