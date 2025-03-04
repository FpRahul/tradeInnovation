@extends('layouts.default')
@section('content')
<style>
    .table.table-condensed.table-striped {
    margin-bottom: 0px;
}
.BI_tablehead {
    background-color: #e5e7eb;
    color: #fff;
}
.table-condensed>thead.BI_tablehead>tr>th {
    padding: 20px 10px 20px 20px;
    text-transform: uppercase;
    font-weight: 400;
    font-size: 14px;
}
.table-striped>tbody>tr:nth-of-type(odd) {
    background-color: #f6f6f6;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border-top: none;
    border-bottom: 2px solid #fff;
}
.table-condensed>tbody.BI_tablebody>tr>td {
    padding: 15px 10px 15px 20px;
    text-transform: capitalize;
    font-weight: 400;
    font-size: 14px;
    color: #4d4d4f;
}

.fixed_header{
    table-layout: fixed;
    border-collapse: collapse;
}

.fixed_header tbody{
  display:block;
  width: 100%;
  overflow: auto;
  max-height: 400px;
}

.fixed_header thead tr {
   display: block;
}

.fixed_header th, .fixed_header td {
  padding: 5px;
  text-align: left;
  width: 200px;
}
</style>
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
        <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] gap-[10px] flex flex-col md:flex-row items-end justify-between">
            <form action="{{ route('lead.paymentStatus') }}" method="GET" id="filterForm" class="w-full flex flex-col md:flex-row gap-[30px]">
                @csrf
                <div class="w-full md:w-9/12 flex items-center gap-[30px]">
                    <div class="w-full flex items-end gap-[20px]">
                        <!-- Lead ID Select (Increased width to 5/12) -->
                        <div class="w-full md:w-5/12">
                            <label for="leadId" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Lead ID</label>
                            <select name="leadId" id="leadId" class="allform-select2 showSourceListName w-full h-[50px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[20px] py-[12px] rounded-[12px] !outline-none">
                                <option value="">Select Lead ID</option>
                                @if ($lead->count()> 0)
                                @foreach ( $lead as $data)
                                <option value="{{ $data->id }}" @if($data->id == $selectedLead) selected @endif>{{ $data->lead_id }} - {{ $data->client_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
    
                        
                        <!-- User Select (visible only for roles 1 or 4, increased width to 5/12) -->
                        <div class="w-full md:w-5/12">
                            <label class="flex text-[15px] text-[#000] mb-[5px]">Date Range</label>
                            <div class="w-[100%] relative">
                                <input type="text" placeholder="Start Date" name="dateRange" class="daterangepicker-item w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" value="">
                                {{-- <i class="ri-calendar-line absolute right-[8px] top-[9px]"></i> --}}
                            </div>
                        </div>
    
                       
    
                        <!-- Filter Button -->
                        <button class="text-[14px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[12px] py-[16px] px-[35px]">
                            Filter
                        </button>
    
                        <!-- Reset Button -->
                        <button id="resetButton" class="text-[14px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[12px] py-[16px] px-[35px]">
                            Reset
                        </button>
                    </div>
                </div>
            </form>
            <div class="relative w-full md:w-[217px] mt-[10px] md:mt-0">
                <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                </svg>
                <input type="search" name="search" id="search" placeholder="Search" class="search !outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px]">
            </div>
        </div>
        
        <div class="overflow-x-auto  " id="search_table_data">
            <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[700px]">
                     <thead>
                        <tr>
                            <th width="15%" class="text-center w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                                S.No
                            </th>
                            <th width="15%" class="whitespace-nowrap text-center w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            Lead ID
                            </th>
                            <th width="15%" class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Client Name
                            </th>
                            <th width="15%" class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Service Name
                            </th>
                            <th width="15%" class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Payment For
                            </th>
                            <th width="15%" class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Total Amount(₹)

                            </th>
                            <th width="15%" class=" whitespace-nowrap text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                Paid ON
                            </th>
                            <th width="15%" class="whitespace-nowrap text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                                View Transaction
                            </th>
                        </tr>
                    </thead> 
                <tbody>
                        @if (count($payment_details) > 0)
                            @foreach ($payment_details as $paymentDetails)
                            <tr>
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                                    {{$loop->index + 1}}
                                </td>
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                                    #{{$paymentDetails->lead->lead_id}}
                                </td>
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                                    {{$paymentDetails->lead->client_name ?? "N/A"}}
                                </td>
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                                    {{$paymentDetails->leadTask->services->serviceName?? "N/A"}}
                                </td>
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                                    {{$paymentDetails->leadTask->serviceSatge->title?? "N/A"}}
                                </td>
                            
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                                    ₹{{ $paymentDetails->total ?? "N/A"}}
                                </td>
                                
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                                    {{ \Carbon\Carbon::parse($paymentDetails->created_at)->format('d M Y') ?? 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center  text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                                    <button  data-modal-target="assignUserModal" data-modal-toggle="assignUserModal" data-rowId = "{{ $paymentDetails->id }}" class=" showPaymentDetails inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_446_3019)">
                                                <path d="M0.762451 7.05005C0.762451 7.05005 3.01245 2.55005 6.94995 2.55005C10.8875 2.55005 13.1375 7.05005 13.1375 7.05005C13.1375 7.05005 10.8875 11.55 6.94995 11.55C3.01245 11.55 0.762451 7.05005 0.762451 7.05005Z" stroke="#13103A" stroke-width="1.125" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.94995 8.73755C7.88193 8.73755 8.63745 7.98203 8.63745 7.05005C8.63745 6.11807 7.88193 5.36255 6.94995 5.36255C6.01797 5.36255 5.26245 6.11807 5.26245 7.05005C5.26245 7.98203 6.01797 8.73755 6.94995 8.73755Z" stroke="#13103A" stroke-width="1.125" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_446_3019">
                                                    <rect width="13.5" height="13.5" fill="white" transform="translate(0.199951 0.300049)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </button>
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
   {{-- modal open --}}
   <div id="assignUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[100%)] max-h-full bg-[rgba(0,0,0,0.6)] ">
    <div class="relative p-4 w-full max-w-[1220px] max-h-full m-auto">
        <!-- Modal content -->
        <div class="relative bg-white  shadow">

            <div class="flex items-center justify-between p-4 md:px-5 md:py-[20px] border-b border-[#f2f2f2]">
                <h3 class="flex items-center gap-[8px] text-[24px] font-[600] leading-[17px] text-[#000]">
                    Payment Details<p id="rowLeadId" class="text-sky-500"></p>
                </h3>

                <button type="button" class="absolute top-[-10px] right-[-10px] w-[35px] h-[35px] bg-[#13103A] flex items-center justify-center text-[#fff] rounded-[60px]" data-modal-hide="assignUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="table-responsive">
                <table width="100%" cellpadding="0" cellspacing="0" class="table table-condensed table-striped fixed_header">
                    <thead class="sticky top-0 bg-[#D9D9D933] z-10 BI_tablehead">
                        <tr>
                            <th width="18%" class="whitespace-nowrap text-center w-[200px] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                               S.No
                            </th>
                            
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               Service Amount
                            </th>
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               Govt Amount
                            </th>
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               GST(18%)
                            </th>
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               Total(₹)
                            </th>
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               Paid Amount(₹)
                            </th>
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               Pending Amount(₹)
                            </th>
                            <th width="18%" class="whitespace-nowrap text-center text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                               Paid ON
                            </th>
                        </tr>
                    </thead> 

                    <tbody id="payment-table-body" class="overflow-y-auto max-h-[300px] BI_tablebody">
                        <!-- Table rows will be appended dynamically -->
                        <tr>
                            <td colspan="6" class="text-center text-red-500">No Record Found!</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>




<script> 
$(document).ready(function(){ 
       $('.showPaymentDetails').on('click', function(){
           var payment_id = $(this).data('rowid');
           $.ajax({
              url: "{{ route('lead.paymentDetails') }}",
              type: "POST",
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              data: {payment_id:payment_id},
              success: function (response){
                        if(response.status == 200) {
                console.log(response);
                $('#payment-table-body').empty();
                    if (response.data.length > 0) {
                        response.data.forEach(function(item, index) {
                            const formattedDate = item.created_at ? moment(item.created_at).format('DD MMM YYYY') : 'N/A';
                            let paidAmount = item.submitted_amount;
                                if (paidAmount == null) {
                                    paidAmount = 0.00;  
                                }
                                var newRow = `
                                    <tr>
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">${index + 1}</td>
                                        
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">₹ ${item.service_price}</td>
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">₹ ${item.govt_price}</td>
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">₹ ${item.gst}</td> <!-- 18% GST -->
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">₹ ${item.total}</td>
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">₹ ${paidAmount}</td>  <!-- Correct variable name used here -->
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">₹ ${item.pending_amount}</td>
                                        <td class="whitespace-nowrap border-b-[1px] border-[#0000001A] text-center text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">${formattedDate}</td>
                                    </tr>
                                `;
                            $('#payment-table-body').append(newRow);
                        });
                    } else {
                        $('#payment-table-body').html("<tr><td colspan="9" class="text-center text-red-500">No Record Found!</td></tr>");
                    }
                } else {
                    console.error('Failed to load payment details');
                }
                        
                    
                       
              }
           })
       })
       var startDate = moment().subtract(7, 'days');
        var endDate = moment();

        $('.daterangepicker-item').daterangepicker({
            opens: 'right',
            startDate: startDate,
            endDate: endDate,
            locale: {
                format: 'DD MMM YYYY'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        $("#resetButton").on('click', function(event) {
            event.preventDefault();
            window.history.replaceState({}, document.title, window.location.pathname);
            window.location.reload();
            $("#filterForm")[0].reset();
        });
})
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
    
</script>
@stop