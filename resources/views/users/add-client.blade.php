@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}} Client</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
        <form method="POST" action="{{ route('users.addclient',['id' => $newClient->id])}}" class="py-[15px] md:py-[25px] px-[15px] md:px-[30px] space-y-[20px]">
            @csrf
            <input type="hidden" name="role" id="role" value="2">
            <div class="flex flex-col md:flex-row gap-[20px] !mt-0">
                <div class="w-full md:w-1/2">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="name" id="name" value="{{ old('name') ? old('name') : $newClient->name}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name" required>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="companyname" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Company Name <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="companyname" id="companyname" value="{{old('companyname') ? old('companyname') : $newClient->companyName}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Company Name" required>
                    @error('companyname')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="scopeofbusiness" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Scope Of Business <strong class="text-[#f83434]">*</strong></label>
                    <select name="scopeofbusiness[]" id="scopeofbusiness" 
                        class="scopeOfBusinessSelect selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" 
                        required multiple>
                        <option value="">Select Scope Of Business</option>                    
                        @if (!empty($scopeOfBussinessList) && $scopeOfBussinessList->isNotEmpty())
                            @foreach ($scopeOfBussinessList as $scopeOfBussinessListDetails)  
                                <option value="{{ $scopeOfBussinessListDetails->id }}" 
                                    @selected(in_array($scopeOfBussinessListDetails->id, old('scopeofbusiness', explode(',', $newClientDetails->business_scope ?? ''))))>
                                    {{ $scopeOfBussinessListDetails->name }}
                                </option>                      
                            @endforeach                                                            
                        @endif
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="otherScopeOfBusinessMain w-full md:w-1/2 hidden">
                    <label for="otherscopeofbusiness" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Other Scope</label>
                    <input type="text" class="otherscopeofbusiness w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" name="otherscopeofbusiness" value="">
                </div>
                <div class="w-full md:w-1/2">
                    <label for="incorporationtype" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Incorporation Type <strong class="text-[#f83434]">*</strong></label>
                    <select name="incorporationtype" class="showPartnerListName selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Select Incorporation Type</option>
                    
                        @if (!empty($incorporationDataList) && $incorporationDataList->isNotEmpty())
                            @foreach ($incorporationDataList as $incorporationDataListDetails)  
                                <option value="{{ $incorporationDataListDetails->id }}" 
                                    @selected(old('incorporationtype', $newClientDetails->incorporationType ?? '') == $incorporationDataListDetails->id)>
                                    {{ $incorporationDataListDetails->name }}
                                </option>                      
                            @endforeach                                                            
                        @endif
                    </select>
                </div>
                @php
                    $displaypartnerClass = 'hidden';
                @endphp
                @if (!empty($partnerDataList))
                    @if ($newClientDetails->incorporationType == 7 || $newClientDetails->incorporationType == null)
                        @php               
                            $displaypartnerClass = '';
                        @endphp
                    @endif                       
                @endif
                <div class="partnerNameDiv w-full md:w-1/2 {{$displaypartnerClass}}" id="source_type">
                    <label for="partnerNameList" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Partners Name</label>
                    <select name="partnerNamelist[]" id="partnerNameList" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none is_required" multiple>
                        <option value="">Select Source Type</option>                    
                        @if ($partnerDataList && $partnerDataList->isNotEmpty())
                            @php
                                // Convert comma-separated partner_id into an array
                                $selectedPartners = explode(',', $newClientDetails->partner_id ?? '');
                            @endphp
                            @foreach ($partnerDataList as $value)
                                <option value="{{ $value->id }}" {{ in_array($value->id, $selectedPartners) ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach                            
                        @endif
                    </select>                                  
                </div>
                
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                {{-- //hhhhhhhhh --}}
                <div class="w-full md:w-1/2">
                    <label for="number" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Mobile Number <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="number" id="number" data-id="{{$newClient->id > 0 ? $newClient->id : 0}}" value="{{ old('number') ? old('number') : $newClient->mobile}}" class="checkDuplicateMobile w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Number" required>
                    <span class="mobile_exist_error text-[#df2727] text-[12px] hidden">Please try with another mobile number!</span>
                    @error('number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="alternatePhone" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Mobile Number</label>
                    <input type="text" name="alternatePhone" id="alternatePhone" value="{{ old('alternatePhone') ? old('alternatePhone') : $newClient->altNumber}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Alternate mobile number">
                    @error('alternatePhone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/2">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email-Id <strong class="text-[#f83434]">*</strong></label>
                    <input type="text" name="email" id="email" value="{{ old('email') ? old('email') : $newClient->email}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email" required>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/2">
                    <label for="alternateEmail" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Alternate Email-Id</label>
                    <input type="text" name="alternateEmail" id="alternateEmail" value="{{ old('alternateEmail') ? old('alternateEmail') : $newClient->altEmail}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Alternate Email">
                    @error('alternateEmail')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col lg:flex-row gap-[20px]">
                <div class="w-full lg:w-1/2">
                    <div class="shadow-lg p-[25px] rounded-[8px] border-[1px] border-[#ccc] currentAddressDiv">                        
                        <div class="mb-[10px]">
                            <label for="currentAddress" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Current Address <strong class="text-[#f83434]">*</strong></label>
                            <textarea type="text" name="currentAddress" id="currentAddress" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Current Address" required>{{old('currentAddress') ? old('currentAddress') : (!empty($newClientDetails->currentAddress) ? $newClientDetails->currentAddress:'') }}</textarea>
                            @error('currentAddress')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-[10px]">
                            <label for="curr_city" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">City</label>
                            <input type="text" name="curr_city" id="curr_city" value="{{ old('curr_city') ? old('curr_city') : (!empty($newClientDetails) ? $newClientDetails->curr_city : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" >
                        </div>
                        <div class="mb-[10px]">
                            <label for="curr_state" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">State</label>
                            <input type="text" name="curr_state" id="curr_state" value="{{ old('curr_state') ? old('curr_state') : (!empty($newClientDetails) ? $newClientDetails->curr_state : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" >
                        </div>
                        <div class="mb-[10px]">
                            <label for="curr_zip" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Zip Code</label>
                            <input type="text" name="curr_zip" id="curr_zip" value="{{ old('curr_zip') ? old('curr_zip') : (!empty($newClientDetails) ? $newClientDetails->curr_zip : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" >
                        </div>                        
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <div class="shadow-lg p-[25px] rounded-[8px] border-[1px] border-[#ccc]">
                        
                        <div class="mb-[10px]">
                            <label for="permanentAddress" class="flex flex-col md:flex-row md:justify-between md:items-center text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">
                                <div class="mb-[10px] md:mb-0">
                                    Permanent Address <strong class="text-[#f83434]">*</strong>
                                </div>
                                <div>                            
                                    <input type="checkbox" name="sameascurrentaddress" class="sameAsCurrentAddress"/>  
                                    <label>Same as current address</label>                          
                                </div>
                            </label>
                            <textarea R type="text" name="permanentAddress" id="permanentAddress" class="w-full h-[120px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Permanent Address" required>{{ old('permanentAddress') ? old('permanentAddress') : (!empty($newClientDetails->permanentAddress) ? $newClientDetails->permanentAddress :'' )}}</textarea>
                            @error('permanentAddress')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-[10px]">
                            <label for="perma_city" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">City</label>
                            <input type="text" name="perma_city" id="perma_city" value="{{ old('perma_city') ? old('perma_city') : (!empty($newClientDetails) ? $newClientDetails->perma_city : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" >
                        </div>
                        <div class="mb-[10px]">
                            <label for="perma_state" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">State</label>
                            <input type="text" name="perma_state" id="perma_state" value="{{ old('perma_state') ? old('perma_state') : (!empty($newClientDetails) ? $newClientDetails->perma_state : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" >
                        </div>
                        <div class="mb-[10px]">
                            <label for="perma_zip" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Zip Code</label>
                            <input type="text" name="perma_zip" id="perma_zip" value="{{ old('perma_zip') ? old('perma_zip') : (!empty($newClientDetails) ? $newClientDetails->perma_zip : '')}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" maxlength="255" >
                        </div> 
                                          
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                
                <div class="w-full md:w-1/2">
                    <label for="referralPartner" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Referral Partner <strong class="text-[#f83434]">*</strong></label>
                    <select name="referralPartner" id="referralPartner" class="showSourceListName selectedValue allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" required>
                        <option value="">Select Referral Partner</option>
                        @if (count($referDataList) > 0)
                            @foreach ($referDataList as $referDataListDetails)  
                                <option value="{{ $referDataListDetails->id }}" 
                                    @selected(old('referralPartner', $newClientDetails->referralPartner ?? '') == $referDataListDetails->id)>
                                    {{ $referDataListDetails->name }}
                                </option>                      
                            @endforeach                                                            
                        @endif
                    </select>
                    
                </div>
                @php
                    $sourceTypeData = [];
                    $displayClass = 'hidden';
                @endphp

            @if (!empty($newClientDetails))

                @if ($newClientDetails->referralPartner == 17 || $newClientDetails->referralPartner == 18 || $newClientDetails->referralPartner == 19)
                    @if ($newClientDetails->source_type_id > 0)           
                        @php                        
                            $sourceTypeData = collect(getUserSourceTypeName($newClientDetails->referralPartner));
                            $displayClass = '';
                        @endphp                        
                    @endif
                @endif
            @endif
            <div class="sourceTypeNameDiv w-full md:w-1/2 {{$displayClass}}" id="source_type">
                <label for="sourceTypeNameList" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Source Type Name</label>
                <select name="sourcetypenamelist" id="sourceTypeNameList" class="allform-select2 w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none is_required">
                    <option value="">Select Source Type</option>
                
                    @if ($sourceTypeData && $sourceTypeData->isNotEmpty())
                        @foreach ($sourceTypeData as $value)
                            <option value="{{ $value->id }}" 
                                @selected(old('sourcetypenamelist', $newClientDetails->source_type_id ?? '') == $value->id)>
                                {{ $value->name }}
                            </option>
                        @endforeach                            
                    @endif
                </select>                
            </div>
            </div>
            <div class="">
                <label for="referralPartner" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Registered under startup scheme</label>
                <div class="flex flex-wrap gap-[20px]">
                    <div>
                        <input type="radio" name="registered" id="registered" value='1' {{$newClientDetails->registered == 1 ?'checked':''}} >
                        <label for="registered" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="registered" id="registered2" value='0' {{$newClientDetails->registered == 0 ?'checked':''}} >
                        <label for="registered2" class="text-[12px] font-[400] leading-[14px] text-[#000000]">No</label>
                    </div>
                </div>
            </div>
            <div class="">
                <label for="msmem" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">MSME Registered Unit</label>
                <div class="flex flex-wrap gap-[20px]">
                    <div>
                        <input type="radio" name="msmem" id="msmem" value='1' {{$newClientDetails->msmem == 1 ?'checked':''}} >
                        <label for="msmem" class="text-[12px] font-[400] leading-[14px] text-[#000000]">Yes</label>
                    </div>
                    <div>
                        <input type="radio" name="msmem" id="msmem2" value='0' {{$newClientDetails->msmem == 0 ?'checked':''}} >
                        <label for="msmem2" class="text-[12px] font-[400] leading-[14px] text-[#000000]">No</label>
                    </div>
                </div>
            </div>
            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('keyup','.checkDuplicateMobile',function(){
        if($(this).val().length >=10){
            let id = $(this).data('id');
            let val = $(this).val();
            let e = $(this);
            $.ajax({
                method:'POST',
                url:"{{ route('user.checkDuplicate')}}",
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                data:{
                    id:id,
                    val:val
                },
                success:function(res){
                    if(res.exists){
                        e.val('');
                        $('.mobile_exist_error').removeClass('hidden');
                    }else{
                        $('.mobile_exist_error').addClass('hidden');
                    }
                }
            });
        }
        
    });

    $(document).on('change','.showSourceListName',function(){
        var value = $(this).val();
        if(value == 17 || value == 18 || value == 19){            
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

    $(document).on('change','.showPartnerListName',function(){ 
        if($(this).val() == 7){
            $('.partnerNameDiv').removeClass('hidden');
        }else{
            $('.partnerNameDiv').addClass('hidden');
        }       
    });

    $(document).on('click','.sameAsCurrentAddress',function(){
        let address = $('.currentAddressDiv').find('#currentAddress').val();
        let city = $('.currentAddressDiv').find('#curr_city').val();
        let state = $('.currentAddressDiv').find('#curr_state').val();
        let zipCode = $('.currentAddressDiv').find('#curr_zip').val();
        
        let peraddress = $(this).parent().parent().parent().find("#permanentAddress");
        let perCity = $(this).parent().parent().parent().parent().find("#perma_city");
        let perState = $(this).parent().parent().parent().parent().parent().find("#perma_state");
        let perZipCode = $(this).parent().parent().parent().parent().parent().find("#perma_zip");
        if($(this).is(':checked')){
            if(address == '' || city == '' || state == '' || zipCode == ''){
                alert("Please fill the current address details");
                $(this).prop("checked", false);
            }else{
                peraddress.val(address);
                perCity.val(city);
                perState.val(state);
                perZipCode.val(zipCode);
            }            
        }else{
            peraddress.val('');
            perCity.val('');
            perState.val('');
            perZipCode.val('');
        }        
    });

    $(document).on('change', '.scopeOfBusinessSelect', function () {
       let hiddenDiv = $(this).parent().parent().find('.otherScopeOfBusinessMain');
        let selectedValues = $(this).val(); 
        if (selectedValues && selectedValues.includes('other')) {
            hiddenDiv.removeClass('hidden');
        } else {
            hiddenDiv.addClass('hidden');
        }
    });

</script>
@stop