@extends('layouts.default')
@section('content')
<?php 
    use App\Models\Role;
?>
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-[20px] gap-[15px]">
        <div>
            <h3 class="text-[20px] font-[500] leading-[24px] text-[#13103A] tracking-[0.02em] mb-1.5">Manage Employees</h3>
            <ul class="flex items-center text-[14px] font-[400] leading-[16px] text-[#000000] gap-[5px]">
                <li>users</li> /
                <li class="text-gray">manage employees</li>
            </ul>
        </div>
        @if(in_array('users.adduser',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
        <div>
            <a href="{{ route('users.adduser')}}" class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.75 5.625H5.625V8.75C5.625 9.09375 5.34375 9.375 5 9.375C4.65625 9.375 4.375 9.09375 4.375 8.75V5.625H1.25C0.90625 5.625 0.625 5.34375 0.625 5C0.625 4.65625 0.90625 4.375 1.25 4.375H4.375V1.25C4.375 0.90625 4.65625 0.625 5 0.625C5.34375 0.625 5.625 0.90625 5.625 1.25V4.375H8.75C9.09375 4.375 9.375 4.65625 9.375 5C9.375 5.34375 9.09375 5.625 8.75 5.625Z" fill="white" />
                </svg>
                Add Users
            </a>
        </div>
        @endif
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] overflow-hidden">
        <div class="py-[15px] md:py-[25px] px-[15px] md:px-[20px] flex items-center justify-end">

            <div class="relative w-[217px]">
                <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
                </svg>
                <input type="search" name="search" id="search" value="{{$searchKey}}" placeholder="Search..." class="search !outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px] ">
            </div>
        </div>
        <div class="overflow-x-auto " id="search_table_data">
            <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[600px]">
                <thead>
                    <tr>
                        <th width="20%" class="text-start w-[200px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                            Name
                        </th>
                        <th width="20%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Mobile Number
                        </th>
                        <th width="20%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Role
                        </th>
                        <th width="20%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Status
                        </th>
                        <th width="20%" class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="get_dynamic_data">
                    @if (count($employeeData) > 0)
                    @foreach ($employeeData as $employeeListing)

                    <tr>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px] pl-[25px]">
                            {{$employeeListing->name}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            {{$employeeListing->mobile}}
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                           {{ Role::find($employeeListing->role)->name ?? 'N/A'; }}
                            
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[12px] px-[15px]">
                            @if ($employeeListing->status == 1)
                            <span class="text-[#13103A] bg-[#99F98C] inline-block text-center min-w-[100px] py-[5px] px-[10px] rounded-[5px] ">Active</span>
                            @else
                            <span class="text-[#13103A] bg-[#f98c8c] inline-block text-center min-w-[100px] py-[5px] px-[10px] rounded-[5px] ">Inactive</span>
                            @endif
                        </td>
                        <td class="border-b-[1px] border-[#0000001A] py-[12px] px-[15px]">
                            @if((in_array('users.adduser',$permissionDetails['accessableRoutes']) || in_array('users.status',$permissionDetails['accessableRoutes']) || in_array('users.delete',$permissionDetails['accessableRoutes'])) || auth()->user()->role==1)
                            <div class="dropdown inline-block relative ml-[auto] mr-[20px] ">
                                <a href="javascript:void(0)" type="button" class="button">
                                    <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z" fill="black" />
                                    </svg>
                                </a>
                                <div class="dropdown_menus absolute right-0 z-10 mt-2 w-[100px] origin-top-right rounded-md bg-white shadow-md ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                    <div class="text-start" role="none">
                                        @if(in_array('users.adduser',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                            <a href="{{ route('users.adduser', ['id' => $employeeListing->id]) }}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Edit</a>
                                        @endif
                                        @if(in_array('users.delete',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                            <a href="#" data-id="{{$employeeListing->id}}" class="delete_employee block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Archive</a>
                                        @endif
                                        @if(in_array('users.status',$permissionDetails['accessableRoutes']) || auth()->user()->role==1)
                                            <a href="{{ route('users.status', ['id' => $employeeListing->id, 'val' => $employeeListing->status]) }}" class="client_status block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">
                                            {{ $employeeListing->status ? 'Inactive' : 'Active' }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                                <x-nopermission />
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center text-red-500">No Record Found!</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <!-- Pagination Links -->
        </div>
        <div id="dynamic-pagination" class="py-[15px] px-[20px]">
            {{ $employeeData->appends(['key' => $searchKey])->links() }}
        </div>
    </div>
</div>
<script>
    $(document).on('keyup', '.search', function() {
        var key = $(this).val();
        $.ajax({
            method: 'POST',
            url: "{{ route('users.listing')}}",
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

    $(document).on('click', '.delete_employee', function() {
        var employeeId = $(this).data('id');
        if (confirm('Are you sure want to delete?')) {
            window.location.href = "{{ route('users.delete', ['id' => ':id']) }}".replace(':id', employeeId);
        }
    });
</script>
@endsection