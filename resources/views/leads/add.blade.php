@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Add Leads</h3>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">
        <form method="POST" action="{{ route('leads.add')}}/{{!empty($leadData) ? $leadData->id :''}}" class="py-[25px] px-[30px] space-y-[20px]">
            @csrf
          
            {{-- // $leadServiceData --}}
            
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="source" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source</label>
                    <select name="source" id="source" class="allform-select2 showSourceListName w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Source Type</option>
                        @if (count($sourceList) > 0 )
                            @foreach ($sourceList as $sourceListData)
                                <option value="{{ $sourceListData->id}}" {{ !empty($leadData) > 0 ? $leadData->source == $sourceListData->id ? 'selected' :'' : ''}}>{{ $sourceListData->name}}</option>
                            @endforeach                            
                        @endif
                    </select>
                </div>
                @php
                $sourceTypeData = [];
                $displayClass = 'hidden';
                @endphp
                @if (!empty($leadData))
                    @if ($leadData->source_id > 0)                
                        @php
                        $sourceTypeData = collect(getSourceTypeName($leadData->source));
                        $displayClass = '';
                        @endphp          
                    @endif
                @endif
               
                <div class="sourceTypeNameDiv w-full md:w-1/2 {{$displayClass}}">
                    <label for="sourceTypeNameList" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source Type Name</label>
                    <select name="sourcetypenamelist" id="sourceTypeNameList" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        @if ($sourceTypeData && $sourceTypeData->isNotEmpty())
                            @foreach ($sourceTypeData as $key =>$value)
                                <option value="{{$value->id}}" {{$leadData->source_id == $value->id ? 'selected':''}}>{{$value->name}}</option>
                            @endforeach                            
                        @endif
                    </select>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="clientname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Client Name</label>
                    <input type="text" name="clientname" id="clientname" value="{{ !empty($leadData) ? $leadData->client_name : ''}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name</label>
                    <input type="text" name="companyname" id="companyname" value="{{!empty($leadData) ? $leadData->company_name : ''}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="mobilenumber" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile number</label>
                    <input type="text" name="mobilenumber" id="mobilenumber" value="{{!empty($leadData) ? $leadData->mobile_number : ''}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id</label>
                    <input type="text" name="email" id="email" value="{{!empty($leadData) ? $leadData->email : ''}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                </div>
                <div class="w-full md:w-1/2">
                    <label for="assign" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Assign to</label>
                    <select name="assign" id="assign" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Assign To Users</option>
                            @foreach ($userList as $userListData)
                            <option 
                            value="{{ $userListData->id }}" 
                            @if(!empty($leadData) && $leadData->assign_to == $userListData->id) selected @endif>
                            {{ $userListData->name }}
                        </option>
                        
                            @endforeach                            
                    </select>
                </div>
            </div>
            <div>
                {{-- service repeater start --}}
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Services</label>
                <div class="leadServiceRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                    <div class="repeater-default">
                        <div data-repeater-list="leadRepeater" class="flex flex-col gap-[20px]">  
                            @if ($leadServiceData && $leadServiceData->isNotEmpty())
                          
                                @foreach ($leadServiceData as $serviceKey => $serviceVal)
                                {{-- {{$serviceVal}} --}}
                                    <div data-repeater-item class="flex flex-wrap items-end gap-[20px]">
                                        <div class="w-[calc(100%-75px)] ">
                                            <input type="hidden" name="lead_id" class="lead_id" value="{{$serviceVal->id}}">
                                            <div class="flex flex-col md:flex-row gap-[20px]">
                                                <div class="w-full md:w-1/2">
                                                    <select name="serviceid" id="serviceid" class="setSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                        <option value="">Service Name</option>
                                                        @if (count($serviceList) > 0)
                                                            @foreach ($serviceList as $serviceListData)
                                                                <option value="{{ $serviceListData->id}}" {{$serviceVal->service_id == $serviceListData->id ? "selected":''}}>{{ $serviceListData->serviceName}}</option>
                                                            @endforeach  
                                                        @endif
                                                        
                                                    </select>
                                                </div>
                                                <div class="w-full md:w-1/2">
                                                    <select name="subserviceid" id="subserviceid" class="getSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                        <option value="">Service Type</option>  
                                                        @if ($serviceVal->subservice_id)                                                           
                                                            @php 
                                                                $subServiceData = getSubService($serviceVal->service_id);
                                                            @endphp                                                      
                                                            @foreach ($subServiceData as $subServiceDataKey => $subServiceDataVal)                                                                
                                                                <option value="{{$subServiceDataVal->id}}" {{$subServiceDataVal->id == $serviceVal->subservice_id ? 'selected':''}}>{{$subServiceDataVal->subServiceName}}</option>                                                                
                                                            @endforeach    
                                                        @endif                                              
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-[55px]">
                                            <span data-repeater-delete data-id="{{$serviceVal->id}}" class="deleteLeadRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
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
                                        <input type="hidden" name="lead_id" class="lead_id" value="0">
                                        <div class="flex flex-col md:flex-row gap-[20px]">
                                            <div class="w-full md:w-1/2">
                                                <select name="serviceid" id="serviceid" class="setSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                                                    <option value="">Service Name</option>
                                                    @if (count($serviceList) > 0)
                                                        @foreach ($serviceList as $serviceListData)
                                                            <option value="{{ $serviceListData->id}}">{{ $serviceListData->serviceName}}</option>
                                                        @endforeach  
                                                    @endif
                                                    
                                                </select>
                                            </div>
                                            <div class="w-full md:w-1/2">
                                                <select name="subserviceid" id="subserviceid" class="getSubService w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
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
                            @endif
                             
                              
                        </div>
                        <div class="mt-[20px]">
                            <a href="javascript:void(0)" data-repeater-create class="inline-block text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]"> Add More </a>
                        </div>
                    </div>
                </div>
                {{-- service repeater end --}}               
            </div>
            {{-- multi attachment --}}
            <div>
                <label class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Attachments</label>
                <div class="leadAttachmentRepeater md:border-[1px] border-[#0000001A] rounded-[10px] md:p-[20px] employee_repeater_wrapper">
                    <div class="repeater-default">
                        <div data-repeater-list="leadAttachment" class="flex flex-col gap-[40px]"> 
                                <div data-repeater-item class="flex  flex-wrap items-end gap-[20px]">
                                    <div class="w-[calc(100%-75px)] ">
                                        <input type="hidden" name="attachment_id" class="attachment_id" value="0">
                                        <div class="flex flex-col md:flex-row gap-[20px]">
                                            <div class="w-full md:w-1/2">
                                                <div class="relative flex flex-wrap items-center gap-[10px]">
                                                    <img src="/assets/images/noimage.png" class="getpreviewImage w-[100%] max-w-[150px] rounded-[10px] object-cover" />
                                                    
                                                    <div class="relative">
                                                        <label for="attachmentFile" class="cursor-pointer w-[50px] h-[50px] rounded-[8px] flex items-center justify-center border border-dashed border-[#13103a4d] ">
                                                            <svg class="cursor-pointer" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M13.9395 8.95044H8.93945V13.9504C8.93945 14.5004 8.48945 14.9504 7.93945 14.9504C7.38945 14.9504 6.93945 14.5004 6.93945 13.9504V8.95044H1.93945C1.38945 8.95044 0.939453 8.50044 0.939453 7.95044C0.939453 7.40044 1.38945 6.95044 1.93945 6.95044H6.93945V1.95044C6.93945 1.40044 7.38945 0.950439 7.93945 0.950439C8.48945 0.950439 8.93945 1.40044 8.93945 1.95044V6.95044H13.9395C14.4895 6.95044 14.9395 7.40044 14.9395 7.95044C14.9395 8.50044 14.4895 8.95044 13.9395 8.95044Z" fill="#13103A" />
                                                            </svg>
                                                        </label>
                                                        <input class="previewImage attachementFile " type="file" name="attachmentFile" id="attachmentFile" >
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                    <div class="w-[55px]">
                                        <span data-repeater-delete data-id="0" class="deleteAttachmentRepeaterRow w-full h-[45px] flex items-center justify-center border-[1px] border-[#0000001A] rounded-[10px] text-center">
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
            </div>

            <div class="">
                <label for="description" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Description</label>
                <textarea type="text" name="description" id="description" class="w-full h-[155px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none">{{!empty($leadData) ? $leadData->description : ''}}</textarea>
            </div>
            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('change', '.previewImage', function () {
        $(this).attr('piy', 'vj');
    });
    
        // $(document).on('change','.previewImage' ,function () { 
        //     $(this).attr('piy','vj');
            // console.log($(this).parent().parent().html());           
            // var input = event.target;
            // var previewContainer = $(this).parent().parent().attr('piy','vj');   
            // return false;         
            // if (input.files && input.files[0]) {
            //     var reader = new FileReader();
            //     reader.onload = function (e) {
            //         previewContainer.attr('src', e.target.result).show();
            //     };
            //     reader.readAsDataURL(input.files[0]);
            // } else {
            //     previewContainer.hide();
            //     previewContainer.attr('src', '');
            // }
        // });
   
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
    $(document).on('change','.showSourceListName',function(){
        var value = $(this).val();
        if(value == 14 || value == 15 || value == 19){
            
            $.ajax({
                method:'POST',
                url:"{{ route('lead.getsourcetypename')}}",
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token()}}'
                },
                dataType:'json',
                data:{
                    value:value
                },
                success:function(res){
                    $('.sourceTypeNameDiv').find('#sourceTypeNameList').html(res.data);
                    $('.sourceTypeNameDiv').css('display','block');
                }
            })
        }else{
            $('.sourceTypeNameDiv').css('display','none');
        }
    });
</script>
@stop