@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}</h3>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('leads.add')}}" class="py-[25px] px-[30px] space-y-[20px]">
            @csrf
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="source" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source</label>
                    <select name="source" id="source" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Source Type</option>
                        @if (count($sourceList) > 0)
                            @foreach ($sourceList as $sourceListData)
                                <option value="{{ $sourceListData->id}}">{{ $sourceListData->name}}</option>
                            @endforeach                            
                        @endif
                    </select>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="clientname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                    <input type="text" name="clientname" id="clientname" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="mobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile number</label>
                    <input type="text" name="mobilenumber" id="mobilenumber" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                    <input type="text" name="email" id="email" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="assign" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign to</label>
                    <select name="assign" id="assign" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Assign To Users</option>
                        @if (count($userList) > 0)
                            @foreach ($userList as $userListData)
                                <option value="{{$userListData->id}}">{{ $userListData->name}}</option>

                            @endforeach                            
                        @endif
                    </select>
                </div>
            </div>
            <div>
                {{-- service repeater start --}}
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Services</label>
                <div class="leadServiceRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                    <div class="repeater-default">
                        <div data-repeater-list="leadRepeater" class="flex flex-col gap-[20px]">  
                            <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                                <div class="w-[calc(100%-75px)] ">
                                    <input type="hidden" name="lead_service_id" class="lead_service_id" value="0">
                                    <div class="flex flex-col md:flex-row gap-[20px]">
                                        <div class="w-full md:w-1/2">
                                            <select name="servicetbl" id="servicetbl" class="setSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                <option value="">Service Name</option>
                                                @if (count($serviceList) > 0)
                                                    @foreach ($serviceList as $serviceListData)
                                                        <option value="{{ $serviceListData->id}}">{{ $serviceListData->serviceName}}</option>
                                                    @endforeach  
                                                @endif
                                                
                                            </select>
                                        </div>
                                        <div class="w-full md:w-1/2">
                                            <select name="subservicetbl" id="subservicetbl" class="getSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                <option value="">Service Type</option>                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-[55px]">
                                    <span data-repeater-delete data-id="0" class="deleteRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
                                        <span class="glyphicon glyphicon-remove"></span>
                                        <svg class="mx-auto" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.616 16C2.15533 16 1.771 15.846 1.463 15.538C1.155 15.23 1.00067 14.8453 1 14.384V2H0V1H4V0.230003H10V1H14V2H13V14.385C13 14.845 12.846 15.2293 12.538 15.538C12.23 15.8467 11.8453 16.0007 11.384 16H2.616ZM4.808 13H5.808V4H4.808V13ZM8.192 13H9.192V4H8.192V13Z" fill="#FF0000" />
                                        </svg>
                                    </span>
                                </div>
                            </div>  
                        </div>
                        <div class="mt-[20px]">
                            <a href="javascript:void(0)" data-repeater-create class="inline-block text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]"> Add More </a>
                        </div>
                    </div>
                </div>
                {{-- service repeater end --}}               
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
<script>
    $(document).on('change','.setSubService',function(){
        var serviceId = $(this).val();
        var e = $(this);
        $.ajax({
            method:'POST',
            url:"{{ route('lead.subservice')}}",
            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dataType:'json',
            data:{
                serviceId:serviceId
            },
            success:function(res){
                e.parent().parent().find('.getSubService').html(res.data);
            }
        });
    });
</script>
@stop