@extends('layouts.default')
@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-[20px] gap-[15px]">
        <div>
            <h3 class="text-[20px] font-[500] leading-[24px] text-[#13103A] tracking-[0.02em] mb-1.5">Manage Payment</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>Lead</li> /
                <li class="text-gray">Manage Payment</li>
            </ul>
        </div>
        
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] ">
        <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] flex items-center justify-end">

            <div class="relative w-full md:w-[217px]">
                 <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                </svg>
                <input type="search" name="search" id="search" placeholder="Search..."  class="search !outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px] ">
            </div>
        </div>
        <div class="overflow-x-auto " id="search_table_data">
        <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[700px]">
    <thead>
        <tr>
            <th width="18%" class="text-start w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                ID
            </th>
            
            <th width="18%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Client Name
            </th>
            <th width="18%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Service Name
            </th>
            
            <th width="18%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Total Amount(₹)
            </th>
            <th width="18%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Paid Amount(₹)
            </th>
            <th width="18%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Pending Amount(₹)
            </th>
            <th width="18%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Paid ON
            </th>
        </tr>
    </thead> 
   <tbody>
        @if (count($payment_details) > 0)
            @foreach ($payment_details as $paymentDetails)
            <tr>
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                    {{$loop->index + 1}}
                </td>
                
                
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                    {{$paymentDetails->lead->client_name ?? "N/A"}}
                </td>
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                    {{$paymentDetails->leadTask->services->serviceName?? "N/A"}}
                </td>
                
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                    ₹{{ $paymentDetails->total ?? "N/A"}}
                </td>
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                    ₹{{ $paymentDetails->submitted_amount ?? "0.00"}}
                </td>
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                    ₹{{ $paymentDetails->pending_amount ?? "N/A"}}
                </td>
                <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                   {{ \Carbon\Carbon::parse($paymentDetails->created_at)->format('d M Y') ?? 'N/A' }}
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center text-red-500">No Record Found!</td>
            </tr>
        @endif
    </tbody> 
</table>
        </div>
        {{-- <div id="dynamic-pagination" class="py-[15px] px-[20px]">
            {{ $associateData->appends(['key' => $searchKey])->links() }}
        </div>  --}}
    </div>
</div>
<script> 
    $(document).on('keyup', '.search', function() {
        var key = $(this).val();
        $.ajax({
            method: 'POST',
            url: "{{ route('associate.listing')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                key: key,
                requestType: 'ajax',
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);
                $('#search_table_data').html(res.trData);

            }
        })

    });
    $(document).on('click', '.delete_associate', function() {
        var employeeId = $(this).data('id');
        if (confirm('Are you sure want to delete?')) {
            window.location.href = "{{ route('users.delete', ['id' => ':id']) }}".replace(':id', employeeId);
        }
    });
</script>
@stop









\