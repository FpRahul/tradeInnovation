@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Sub Service</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
        <form method="POST" action="{{ route('services.subService.add',['id'=>$subServiceList[0]['id']])}}" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
            @csrf
            <div class="flex flex-col md:flex-row gap-[20px]">                
                <div class="w-full">
                    <label for="service" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Service</label>
                    <input type="text" name="serviceName" id="serviceName" value="{{ $subServiceList[0]['serviceName']}}"
                    class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                    placeholder="Service Name" disabled>
                </div>
            </div>
            <div class="">
                <div class="subservicerepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                    <div class="repeater-default">
                        <div data-repeater-list="subservice" class="flex flex-col gap-[20px]">   
                            @if (count($subServiceList[0]['sub_service']) > 0)
                                @foreach ($subServiceList[0]['sub_service'] as $subServiceListt)                                   
                                                      
                                <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                                    <div class="w-[calc(100%-75px)]">
                                        <input type="hidden" name="subservice_id" class="subservice_id" value="{{ $subServiceListt['id']}}">
                                        <div class="w-full">
                                            <label for="subservice" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                                Sub Service
                                            </label>
                                            <input type="text" name="subservice" id="subservice" value="{{ $subServiceListt['subServiceName']}}"
                                                class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                                                placeholder="Sub Service" required>
                                        </div>                                  
                                    </div>
                                    <div class="w-[55px]">
                                        <span data-repeater-delete data-id="{{ $subServiceListt['id']}}" class="deleteRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
                                            <span class="glyphicon glyphicon-remove"></span>
                                            <svg class="mx-auto" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.616 16C2.15533 16 1.771 15.846 1.463 15.538C1.155 15.23 1.00067 14.8453 1 14.384V2H0V1H4V0.230003H10V1H14V2H13V14.385C13 14.845 12.846 15.2293 12.538 15.538C12.23 15.8467 11.8453 16.0007 11.384 16H2.616ZM4.808 13H5.808V4H4.808V13ZM8.192 13H9.192V4H8.192V13Z" fill="#FF0000" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>                              
                            @endforeach
                            @else
                            <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                                <div class="w-[calc(100%-75px)] ">
                                    <input type="hidden" name="subservice_id" class="subservice_id" value="0">
                                    <div class="w-full">
                                        <label for="subservice" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                            Sub Service
                                        </label>
                                        <input type="text" name="subservice" id="subservice"
                                            class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none"
                                            placeholder="Sub Service" required>
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
                            @endif 
                        </div>
                        <div class="mt-[20px]">
                            <a href="javascript:void(0)" data-repeater-create class="inline-block text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]"> Add More </a>
                        </div>
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