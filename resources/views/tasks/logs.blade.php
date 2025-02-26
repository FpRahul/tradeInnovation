@extends('layouts.default')
@section('content')

<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px]">
    <div class="px-[15px] md:px-[30px] py-[20px] border-b-[1px] border-[#0000001A] flex flex-wrap items-center justify-between ">
        <div class="flex items-center gap-[8px]">
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 7H3.83L9.42 1.41L8 0L0 8L8 16L9.41 14.59L3.83 9H16V7Z" fill="black" />
                </svg>
            </a>
            <h3 class="text-[17px] leading-[26px] font-[600] tracking-[-0.03em] ">Client Information</h3>
        </div>
        <p class="text-[14px] leading-[16px] font-[600] text-[#2C2C2C] ">Action summary on Rahul Chouhan (lead <span class="text-[#2196F3]">#001</span>)</p>
    </div>
    <div class="px-[15px] md:px-[30px] py-[20px]">
        <ul class="grid grid-cols-2 lg:grid-cols-3 gap-[20px]">
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Client name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Rahul Chouhan</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Company Name</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Futureprofilez</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Services</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Trademark</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Mobile Number</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">123456789</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Inbound Date</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">23 Dec 2024</strong>
            </li>
            <li>
                <span class="block text-[14px] leading-[16px] font-[500] tracking-[-0.03em] text-[#666666] capitalize mb-1">Associate</span>
                <strong class="block text-[16px] leading-[21px] font-[600] tracking-[-0.03em] text-[#1B1B1B] capitalize ">Surya</strong>
            </li>
        </ul>
    </div>
</div>

<div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mt-[20px] overflow-hidden">
    <div class="py-[15px] px-[15px] md:px-[20px] gap-[10px] flex flex-col md:flex-row items-center justify-between">
        <h3 class="text-[17px] leading-[26px] font-[600] tracking-[-0.03em] ">Recent Activity</h3>
        <div class="relative w-full md:w-[217px]">
            <svg class="absolute top-[50%] left-[13px] translate-y-[-50%]" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6381 12.2923C14.8254 10.761 15.385 8.83464 15.203 6.9052C15.021 4.97576 14.111 3.18816 12.6583 1.90607C11.2055 0.62398 9.31913 -0.0562918 7.38281 0.00364974C5.4465 0.0635913 3.60574 0.859243 2.23502 2.22874C0.863103 3.59918 0.0651678 5.44139 0.00381831 7.37995C-0.0575312 9.3185 0.622323 11.2075 1.90484 12.662C3.18735 14.1165 4.976 15.0271 6.90629 15.2081C8.83659 15.3892 10.7632 14.8271 12.2936 13.6364L12.3346 13.6792L16.3737 17.7209C16.4621 17.8094 16.5671 17.8796 16.6827 17.9275C16.7983 17.9753 16.9222 18 17.0473 18C17.1724 18 17.2963 17.9753 17.4119 17.9275C17.5275 17.8796 17.6325 17.8094 17.721 17.7209C17.8094 17.6324 17.8796 17.5273 17.9275 17.4117C17.9754 17.296 18 17.1721 18 17.0469C18 16.9218 17.9754 16.7978 17.9275 16.6822C17.8796 16.5666 17.8094 16.4615 17.721 16.373L13.6809 12.3323L13.6381 12.2923ZM11.6614 3.57658C12.199 4.1057 12.6266 4.73606 12.9194 5.43131C13.2123 6.12655 13.3646 6.87293 13.3677 7.62737C13.3708 8.38182 13.2245 9.12941 12.9373 9.82702C12.6501 10.5246 12.2277 11.1585 11.6944 11.6919C11.1612 12.2254 10.5276 12.648 9.83027 12.9353C9.13294 13.2226 8.38565 13.3689 7.6315 13.3658C6.87736 13.3628 6.13128 13.2104 5.43631 12.9174C4.74134 12.6244 4.11123 12.1967 3.58233 11.6589C2.52535 10.5841 1.93571 9.13508 1.94185 7.62737C1.94799 6.11967 2.5494 4.67547 3.61509 3.60936C4.68078 2.54325 6.1244 1.94159 7.6315 1.93545C9.13861 1.92931 10.5871 2.51919 11.6614 3.57658Z" fill="#6F6F6F" />
            </svg>
            <input type="search" name="search" id="search" placeholder="Search by role name" class="!outline-none border border-[#0000001A] h-[40px] w-full p-[10px] pl-[42px] bg-transparent text-[#000000] placeholder:text-[#6F6F6F] rounded-[10px] text-[14px] font-[400] leading-[16px] ">
        </div>
    </div>
    <div class="overflow-x-auto ">
        <table width="100%" cellpadding="0" cellspacing="0" class="min-w-[900px]">
            <thead>
                <tr>
                    <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                        Client name
                    </th>
                    <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                        Lead Id
                    </th>
                    <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                        Services
                    </th>
                    <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                        Activity
                    </th>
                    <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                        Log Date
                    </th>
                    <th class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                        Rahul
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        #004
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Trademark, Patent
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Document verified on the government portal
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        2023-02-19
                    </td>
                    <td class="text-center border-b-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        <a href="#" class="inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
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
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                        Rahul
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        #004
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Trademark, Patent
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Document verified on the government portal
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        2023-02-19
                    </td>
                    <td class="text-center border-b-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        <a href="#" class="inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
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
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                        Rahul
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        #004
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Trademark, Patent
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Document verified on the government portal
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        2023-02-19
                    </td>
                    <td class="text-center border-b-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        <a href="#" class="inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
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
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                        Rahul
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        #004
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Trademark, Patent
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Document verified on the government portal
                    </td>
                    <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        2023-02-19
                    </td>
                    <td class="text-center border-b-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        <a href="#" class="inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
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
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class=" text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                        Rahul
                    </td>
                    <td class=" text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        #004
                    </td>
                    <td class=" text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Trademark, Patent
                    </td>
                    <td class=" text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        Document verified on the government portal
                    </td>
                    <td class=" text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        2023-02-19
                    </td>
                    <td class="text-center  text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                        <a href="#" class="inline-flex w-[27px] h-[27px] items-center justify-center bg-[#F1F3F4] rounded-[100%]">
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
                        </a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@stop