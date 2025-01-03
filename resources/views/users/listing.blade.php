@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}</h3>
        <a href="{{ route('users.adduser')}}" class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.75 5.625H5.625V8.75C5.625 9.09375 5.34375 9.375 5 9.375C4.65625 9.375 4.375 9.09375 4.375 8.75V5.625H1.25C0.90625 5.625 0.625 5.34375 0.625 5C0.625 4.65625 0.90625 4.375 1.25 4.375H4.375V1.25C4.375 0.90625 4.65625 0.625 5 0.625C5.34375 0.625 5.625 0.90625 5.625 1.25V4.375H8.75C9.09375 4.375 9.375 4.65625 9.375 5C9.375 5.34375 9.09375 5.625 8.75 5.625Z" fill="white" />
            </svg>
            Add Users
        </a>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] ">
        <div class="py-[25px] px-[20px] flex items-center justify-between">
            <div>
                <select name="sorting" id="sorting" class="!outline-none h-[40px] border border-[#0000001A] w-[66px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] ">
                    <option value="1">1</option>
                    <option value="1">2</option>
                    <option value="1">3</option>
                    <option value="1">4</option>
                    <option value="1">5</option>
                    <option value="1">6</option>
                    <option value="1">7</option>
                    <option value="1">8</option>
                    <option value="1">9</option>
                    <option value="1" selected>10</option>
                </select>
            </div>
            <div class="relative w-[217px]">
                <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                </svg>
                <input type="search" name="search" id="search" placeholder="Search by role name" class="search !outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px] ">
            </div>
        </div>
        <div class="overflow-x-auto ">
            <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[700px]">
                <thead>
                    <tr>
                        <th width="25%" class="text-start w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            name
                        </th>
                        <th width="25%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            mobile number
                        </th>
                        <th width="25%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            status
                        </th>
                        <th width="25%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($employeeData as $employeeListing)                      
                  
                    <tr>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                            {{$employeeListing->name}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$employeeListing->mobile}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            @if ($employeeListing->status == 1)
                            <span class="text-[#13103A] bg-[#99F98C] inline-block text-center min-w-[100px] py-[5px] px-[10px] rounded-[5px] ">Active</span>
                            @else
                            <span class="text-[#13103A] bg-[#f98c8c] inline-block text-center min-w-[100px] py-[5px] px-[10px] rounded-[5px] ">Inactive</span>
                            @endif                        </td>
                        <td class="border-b-[1px] border-[#0000001A] py-[12px] px-[15px]">
                            
                            <div class="flex items-center flex-wrap gap-[7px]">
                                <a href="{{ route('users.adduser', ['id' => $employeeListing->id]) }}" class="bg-[#13103A] w-[27px] h-[27px] rounded-[100%] text-center border-none p-0 ">
                                    <svg class="mx-auto" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_455_4936)">
                                            <path d="M6.89951 2.69995H2.69951C2.38125 2.69995 2.07603 2.82638 1.85098 3.05142C1.62594 3.27647 1.49951 3.58169 1.49951 3.89995V12.3C1.49951 12.6182 1.62594 12.9234 1.85098 13.1485C2.07603 13.3735 2.38125 13.5 2.69951 13.5H11.0995C11.4178 13.5 11.723 13.3735 11.948 13.1485C12.1731 12.9234 12.2995 12.6182 12.2995 12.3V8.09995" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M11.3996 1.80004C11.6383 1.56134 11.962 1.42725 12.2996 1.42725C12.6372 1.42725 12.9609 1.56134 13.1996 1.80004C13.4383 2.03873 13.5724 2.36247 13.5724 2.70004C13.5724 3.0376 13.4383 3.36134 13.1996 3.60004L7.49961 9.30004L5.09961 9.90004L5.69961 7.50004L11.3996 1.80004Z" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_455_4936">
                                                <rect width="14.4" height="14.4" fill="white" transform="translate(0.299805 0.300049)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                                <button data-id="{{$employeeListing->id}}" class="delete_employee bg-[red] w-[27px] h-[27px] rounded-[100%] text-center border-none p-0">
                                    <svg class="mx-auto w-[15px] h-[15px]" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.66666 12C2.29999 12 1.98621 11.8696 1.72532 11.6087C1.46443 11.3478 1.33377 11.0338 1.33332 10.6667V2H0.666656V0.666667H3.99999V0H7.99999V0.666667H11.3333V2H10.6667V10.6667C10.6667 11.0333 10.5362 11.3473 10.2753 11.6087C10.0144 11.87 9.70043 12.0004 9.33332 12H2.66666ZM3.99999 9.33333H5.33332V3.33333H3.99999V9.33333ZM6.66666 9.33333H7.99999V3.33333H6.66666V9.33333Z" fill="white" />
                                    </svg>
                                </button>
                                <!-- Checkbox Switch -->
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" data-id="{{$employeeListing->id}}" class="client_status sr-only peer !outline-none !shadow-none " {{$employeeListing->status ? 'checked':''}}>
                                    <div class=" w-10 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 transition-all"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transform peer-checked:translate-x-4 transition-transform"></div>
                                </label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                   
                </tbody>
            </table>
                <!-- Pagination Links -->
                <div style="margin-top: 20px;">
                    {{ $employeeData }}
                </div>
        </div>
    </div>
</div>
<script>
    $(document).on('keyup','.search',function(){
        var key = $(this).val();
        if(key != ''){
            $.ajax({
                method:'POST',
                url:"{{ route('users.listing')}}",
                data:{
                    key:key,
                    _token:'{{ csrf_token() }}'
                },
                success:function(res){

                }
            })
        }        
    });

    $(document).on('click','.delete_employee',function(){
        var employeeId = $(this).data('id');
        if(confirm('Are you sure want to delete?')){
            $.ajax({
                method:'POST',
                url:"{{route ('users.delete')}}",
                data:{
                    employeeId:employeeId,
                    _token: '{{ csrf_token() }}'
                },
                success:function(res){
                    if(res == 'deleted'){
                        window.location.reload();
                    }
                }
            })
        }
        
    });
    $(document).on('click','.client_status',function(){
        var userId = $(this).data('id');
        if($(this).is(':checked')){
            var statusVal = 1;
        }else{
            var statusVal = 0;
        }
        $.ajax({
            method:'POST',
            url:'{{ route('users.status')}}',
            data:{
                _token:'{{csrf_token()}}',
                statusVal:statusVal,
                userId:userId
            },
            success:function(res){
               if(res == 'status changed'){
                window.location.reload();
               }
            }
        })
    });
</script>
@endsection