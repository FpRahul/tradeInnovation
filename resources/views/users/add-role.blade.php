@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Create Role</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] mb-[30px]">
        <form>
            <div class="py-[25px] px-[20px]">
                <label for="rolename" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Role Name</label>
                <input type="text" name="rolename" id="rolename" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Role Name">
            </div>
            <div class="overflow-x-auto border-b-[1px] border-[#0000001A]">
                <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[700px]">
                    <thead>
                        <tr>
                            <th class="w-[70px] text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px] pl-[30px]">
                                <input type="checkbox" name="selectall" id="selectall" class="w-[12px] h-[12px] border-[#0000001A] ">
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Module
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Permissions
                            </th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]"></th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]"></th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]"></th>
                            <th class="text-start bg-[#D9D9D933] text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px] pl-[30px]">
                                <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Users
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    manage
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Create
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="py-[14px] px-[15px]">
                                <button class="bg-transparent border-none p-0 ">
                                    <svg class="w-[12px]" width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 18C2.45 18 1.97934 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3C0.71667 3 0.479337 2.904 0.288004 2.712C0.0966702 2.52 0.000670115 2.28267 3.44827e-06 2C-0.000663218 1.71733 0.0953369 1.48 0.288004 1.288C0.48067 1.096 0.718003 1 1 1H5C5 0.716667 5.096 0.479333 5.288 0.288C5.48 0.0966668 5.71734 0.000666667 6 0H10C10.2833 0 10.521 0.0960001 10.713 0.288C10.905 0.48 11.0007 0.717333 11 1H15C15.2833 1 15.521 1.096 15.713 1.288C15.905 1.48 16.0007 1.71733 16 2C15.9993 2.28267 15.9033 2.52033 15.712 2.713C15.5207 2.90567 15.2833 3.00133 15 3V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM6 14C6.28334 14 6.521 13.904 6.713 13.712C6.905 13.52 7.00067 13.2827 7 13V6C7 5.71667 6.904 5.47933 6.712 5.288C6.52 5.09667 6.28267 5.00067 6 5C5.71734 4.99933 5.48 5.09533 5.288 5.288C5.096 5.48067 5 5.718 5 6V13C5 13.2833 5.096 13.521 5.288 13.713C5.48 13.905 5.71734 14.0007 6 14ZM10 14C10.2833 14 10.521 13.904 10.713 13.712C10.905 13.52 11.0007 13.2827 11 13V6C11 5.71667 10.904 5.47933 10.712 5.288C10.52 5.09667 10.2827 5.00067 10 5C9.71734 4.99933 9.48 5.09533 9.288 5.288C9.096 5.48067 9 5.718 9 6V13C9 13.2833 9.096 13.521 9.288 13.713C9.48 13.905 9.71734 14.0007 10 14Z" fill="#FF2F00" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px] pl-[30px]">
                                <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Roles
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    manage
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Create
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="py-[14px] px-[15px]">
                                <button class="bg-transparent border-none p-0 ">
                                    <svg class="w-[12px]" width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 18C2.45 18 1.97934 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3C0.71667 3 0.479337 2.904 0.288004 2.712C0.0966702 2.52 0.000670115 2.28267 3.44827e-06 2C-0.000663218 1.71733 0.0953369 1.48 0.288004 1.288C0.48067 1.096 0.718003 1 1 1H5C5 0.716667 5.096 0.479333 5.288 0.288C5.48 0.0966668 5.71734 0.000666667 6 0H10C10.2833 0 10.521 0.0960001 10.713 0.288C10.905 0.48 11.0007 0.717333 11 1H15C15.2833 1 15.521 1.096 15.713 1.288C15.905 1.48 16.0007 1.71733 16 2C15.9993 2.28267 15.9033 2.52033 15.712 2.713C15.5207 2.90567 15.2833 3.00133 15 3V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM6 14C6.28334 14 6.521 13.904 6.713 13.712C6.905 13.52 7.00067 13.2827 7 13V6C7 5.71667 6.904 5.47933 6.712 5.288C6.52 5.09667 6.28267 5.00067 6 5C5.71734 4.99933 5.48 5.09533 5.288 5.288C5.096 5.48067 5 5.718 5 6V13C5 13.2833 5.096 13.521 5.288 13.713C5.48 13.905 5.71734 14.0007 6 14ZM10 14C10.2833 14 10.521 13.904 10.713 13.712C10.905 13.52 11.0007 13.2827 11 13V6C11 5.71667 10.904 5.47933 10.712 5.288C10.52 5.09667 10.2827 5.00067 10 5C9.71734 4.99933 9.48 5.09533 9.288 5.288C9.096 5.48067 9 5.718 9 6V13C9 13.2833 9.096 13.521 9.288 13.713C9.48 13.905 9.71734 14.0007 10 14Z" fill="#FF2F00" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px] pl-[30px]">
                                <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Leads
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    manage
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Create
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="py-[14px] px-[15px]">
                                <button class="bg-transparent border-none p-0 ">
                                    <svg class="w-[12px]" width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 18C2.45 18 1.97934 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3C0.71667 3 0.479337 2.904 0.288004 2.712C0.0966702 2.52 0.000670115 2.28267 3.44827e-06 2C-0.000663218 1.71733 0.0953369 1.48 0.288004 1.288C0.48067 1.096 0.718003 1 1 1H5C5 0.716667 5.096 0.479333 5.288 0.288C5.48 0.0966668 5.71734 0.000666667 6 0H10C10.2833 0 10.521 0.0960001 10.713 0.288C10.905 0.48 11.0007 0.717333 11 1H15C15.2833 1 15.521 1.096 15.713 1.288C15.905 1.48 16.0007 1.71733 16 2C15.9993 2.28267 15.9033 2.52033 15.712 2.713C15.5207 2.90567 15.2833 3.00133 15 3V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM6 14C6.28334 14 6.521 13.904 6.713 13.712C6.905 13.52 7.00067 13.2827 7 13V6C7 5.71667 6.904 5.47933 6.712 5.288C6.52 5.09667 6.28267 5.00067 6 5C5.71734 4.99933 5.48 5.09533 5.288 5.288C5.096 5.48067 5 5.718 5 6V13C5 13.2833 5.096 13.521 5.288 13.713C5.48 13.905 5.71734 14.0007 6 14ZM10 14C10.2833 14 10.521 13.904 10.713 13.712C10.905 13.52 11.0007 13.2827 11 13V6C11 5.71667 10.904 5.47933 10.712 5.288C10.52 5.09667 10.2827 5.00067 10 5C9.71734 4.99933 9.48 5.09533 9.288 5.288C9.096 5.48067 9 5.718 9 6V13C9 13.2833 9.096 13.521 9.288 13.713C9.48 13.905 9.71734 14.0007 10 14Z" fill="#FF2F00" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px] pl-[30px]">
                                <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Reports
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    manage
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Create
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="py-[14px] px-[15px]">
                                <button class="bg-transparent border-none p-0 ">
                                    <svg class="w-[12px]" width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 18C2.45 18 1.97934 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3C0.71667 3 0.479337 2.904 0.288004 2.712C0.0966702 2.52 0.000670115 2.28267 3.44827e-06 2C-0.000663218 1.71733 0.0953369 1.48 0.288004 1.288C0.48067 1.096 0.718003 1 1 1H5C5 0.716667 5.096 0.479333 5.288 0.288C5.48 0.0966668 5.71734 0.000666667 6 0H10C10.2833 0 10.521 0.0960001 10.713 0.288C10.905 0.48 11.0007 0.717333 11 1H15C15.2833 1 15.521 1.096 15.713 1.288C15.905 1.48 16.0007 1.71733 16 2C15.9993 2.28267 15.9033 2.52033 15.712 2.713C15.5207 2.90567 15.2833 3.00133 15 3V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM6 14C6.28334 14 6.521 13.904 6.713 13.712C6.905 13.52 7.00067 13.2827 7 13V6C7 5.71667 6.904 5.47933 6.712 5.288C6.52 5.09667 6.28267 5.00067 6 5C5.71734 4.99933 5.48 5.09533 5.288 5.288C5.096 5.48067 5 5.718 5 6V13C5 13.2833 5.096 13.521 5.288 13.713C5.48 13.905 5.71734 14.0007 6 14ZM10 14C10.2833 14 10.521 13.904 10.713 13.712C10.905 13.52 11.0007 13.2827 11 13V6C11 5.71667 10.904 5.47933 10.712 5.288C10.52 5.09667 10.2827 5.00067 10 5C9.71734 4.99933 9.48 5.09533 9.288 5.288C9.096 5.48067 9 5.718 9 6V13C9 13.2833 9.096 13.521 9.288 13.713C9.48 13.905 9.71734 14.0007 10 14Z" fill="#FF2F00" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px] pl-[30px]">
                                <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                Services
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    manage
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Create
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="text-start text-[14px] font-[400] leading-[16px] text-[#000000] py-[14px] px-[15px]">
                                <label>
                                    <input type="checkbox" name="users" id="users" class="w-[12px] h-[12px]">
                                    Edit
                                </label>
                            </td>
                            <td class="py-[14px] px-[15px]">
                                <button class="bg-transparent border-none p-0 ">
                                    <svg class="w-[12px]" width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 18C2.45 18 1.97934 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3C0.71667 3 0.479337 2.904 0.288004 2.712C0.0966702 2.52 0.000670115 2.28267 3.44827e-06 2C-0.000663218 1.71733 0.0953369 1.48 0.288004 1.288C0.48067 1.096 0.718003 1 1 1H5C5 0.716667 5.096 0.479333 5.288 0.288C5.48 0.0966668 5.71734 0.000666667 6 0H10C10.2833 0 10.521 0.0960001 10.713 0.288C10.905 0.48 11.0007 0.717333 11 1H15C15.2833 1 15.521 1.096 15.713 1.288C15.905 1.48 16.0007 1.71733 16 2C15.9993 2.28267 15.9033 2.52033 15.712 2.713C15.5207 2.90567 15.2833 3.00133 15 3V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM6 14C6.28334 14 6.521 13.904 6.713 13.712C6.905 13.52 7.00067 13.2827 7 13V6C7 5.71667 6.904 5.47933 6.712 5.288C6.52 5.09667 6.28267 5.00067 6 5C5.71734 4.99933 5.48 5.09533 5.288 5.288C5.096 5.48067 5 5.718 5 6V13C5 13.2833 5.096 13.521 5.288 13.713C5.48 13.905 5.71734 14.0007 6 14ZM10 14C10.2833 14 10.521 13.904 10.713 13.712C10.905 13.52 11.0007 13.2827 11 13V6C11 5.71667 10.904 5.47933 10.712 5.288C10.52 5.09667 10.2827 5.00067 10 5C9.71734 4.99933 9.48 5.09533 9.288 5.288C9.096 5.48067 9 5.718 9 6V13C9 13.2833 9.096 13.521 9.288 13.713C9.48 13.905 9.71734 14.0007 10 14Z" fill="#FF2F00" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-[30px]">
                <button class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

@endsection