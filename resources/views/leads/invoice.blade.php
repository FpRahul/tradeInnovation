@extends('layouts.default')
@section('content')
<div class="w-full bg-[#fff] shadow-[] border-[1px] border-[#f2f2f2] p-[15px] rounded-[10px]">
    <div class="flex items-center gap-[15px] justify-between">
        <h2 class="mb-[30px] text-[30px] font-[600] text-[#000]">Invoide</h2>
        <div class="w-[100%] max-w-[200px] relative ">
           
            <input 
                type="text" 
                placeholder="Start Date" 
                name="startDate" 
                class="daterangepicker-item daterangepicker-startDate w-[100%] h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] outline-none" 
                value="" 
                autocomplete="off"
            >
           
        </div>  
    </div>
    <div class="mt-[25px] text-left text-[25px] font-[600] mb-[15px]">
        This Heading Text
    </div>

    <div class="w-full overflow-x-auto">
        <table class="w-full border-[1px] border-[#ccc]">
            <tr>
                <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff] text-left ">Name</th>
                <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff]  text-left">Email Id</th>
                <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff]  text-left">Mobile</th>
                <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff] text-left">Firm</th>
            </tr>


            <tr class="border-b-[1px] border-b-[#ccc]">
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="email" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
            </tr>

            <tr class="border-b-[1px] border-b-[#ccc]">
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="email" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                <td class="p-[10px] font-[600] text-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
            </tr>

        </table>
    </div>


    <div class="mt-[50px]">
        <h2 class="mb-[25px] text-left text-[25px] font-[600] mb-[15px]">Services</h2>
        <div class="overflow-x-auto">
            <table class="border-[1px] border-[#ccc] w-full">
                <tr>
                    <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff] text-left  ">Name</th>
                    <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff] text-left  ">Govt. Cost</th>
                    <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff] text-left  ">Self Cost</th>
                    <th class="bg-[#13103a] p-[10px] font-[600] text-[15px] text-[#fff] text-left  ">GST</th>
                </tr>


                <tr class="border-b-[1px] border-b-[#ccc]">
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                </tr>

                <tr class="border-b-[1px] border-b-[#ccc]">
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                    <td class="p-[10px] font-[600] font-[15px] text-[#000] "><input type="text" class="px-[15px] pr-[18px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] font-[15px] font-[#000]" placeholder="Name" /></td>
                </tr>

            </table>
        </div>
    </div>

    <p class="mt-[40px] text-[15px] text-[#999]">This Invoide Sheet</p>

    <div class="mt-[50px] flex gap-[10px] justify-end">
        <button class="px-[15px] py-[10px] bg-[#13103a] text-[15px] text-[#fff] rounded-[5px]">Save & Download</button>
        <button class="px-[15px] py-[10px] bg-[#13103a] text-[15px] text-[#fff] rounded-[5px]">Save & Email</button>
    </div>

</div>
<script>
 $(document).ready(function() {
        function initializeDatePicker(element) {
            element.daterangepicker({
                singleDatePicker: true,
                opens: 'right',
                locale: {
                    format: 'DD MMM YYYY'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                console.log("A new date selection was made: " + picker.startDate.format('YYYY-MM-DD'));
            });
        }

        initializeDatePicker($('.daterangepicker-startDate'));

        $(document).on('focus', '.daterangepicker-startDate', function() {
            if (!$(this).data('daterangepicker')) {
                initializeDatePicker($(this));
            }
        });
    });
</script>
@stop