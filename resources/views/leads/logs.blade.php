@extends('layouts.default')
@section('content')
<style>
    .modal-style {
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        border: 1px solid rgba(0, 0, 0, .2)
    }
</style>
<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Lead logs </h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[20px] p-[23px]">
        <form id="filterForm" action="" class="w-full" method="GET">
            <div class="flex items-end gap-[10px] w-full">
                <div class="w-[50%]">
                    <label class="flex text-[15px] text-[#000] mb-[5px]">Lead ID<strong class="text-[#f83434]">*</strong></label>
                    <select name="lead_id" id="lead_id" class="allform-filter-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A] ">
                        <option value="">Select Lead ID</option>
                        @forelse($leadData as $leadDetails)
                        <option value="{{ $leadDetails->id }}" @if(isset($requestParams['lead_id']) && $requestParams['lead_id']==$leadDetails->id) selected @endif> {{ $leadDetails->lead_id }} - {{ $leadDetails->client_name }} </option>
                        @empty
                        <option value="" disabled>No leads available</option>
                        @endforelse
                    </select>
                </div>
                <button class=" text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">Filter</button>
                <button id="resetButton" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[15px] px-[30px]">
                    Reset
                </button>
            </div>
        </form>
    </div>
    <div hidden id="showLog" class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] p-[23px]">
        <div>
            <ul>
                @foreach($leadLogs as $log)
                <li class="py-[6px] pt-0 flex items-start flex-wrap gap-[16px] relative">
                    <div class="static min-w-[34px] w-[34px] h-[34px] bg-[#13103A] rounded-[100%] flex items-center justify-center">
                        <img src="{{ asset('assets/images/list-icon-img.png') }}" alt="icon" class="relative z-2">
                        <div class="absolute top-0 left-[17px] h-full border-dashed w-[1px] border-r border-[rgba(0,0,0,0.2)]"></div>
                    </div>
                    <div class="w-[calc(100%-50px)] bg-[#EFEDFF] rounded-[10px] p-[12px]">
                        <div class="flex items-center gap-[15px]">
                            <span class="text-[13px] leading-[16px] font-[400] tracking-[-0.04em] text-[#454545] ">{{ \Carbon\Carbon::parse($log->created_at)->format('j M Y') }}
                            </span>
                            <div class="flex items-center gap-[7px]">
                                <span class="inline-flex items-center justify-center w-[22px] h-[22px] border border-[#0000001A] rounded-[5px]">
                                    <svg width="8" height="10" viewBox="0 0 8 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.6501 0.854272C2.18493 0.307291 2.91031 0 3.66667 0C4.04118 0 4.41202 0.0754418 4.75802 0.222018C5.10403 0.368594 5.41841 0.583434 5.68323 0.854272C5.94805 1.12511 6.15812 1.44664 6.30143 1.80051C6.44475 2.15437 6.51852 2.53364 6.51852 2.91667C6.51852 3.29969 6.44475 3.67896 6.30143 4.03283C6.18381 4.32325 6.02123 4.59189 5.82032 4.8286C6.74243 5.45021 7.33333 6.53854 7.33333 7.91667V10H0V7.91667C0 6.53788 0.591893 5.44805 1.51271 4.82825C1.0641 4.29956 0.814815 3.62126 0.814815 2.91667C0.814815 2.14312 1.11528 1.40125 1.6501 0.854272ZM1.52194 5.90446C1.08859 6.38183 0.814815 7.06214 0.814815 7.91667V9.16667H3.25926V7.76329L1.52194 5.90446ZM4.07407 7.78399V9.16667H6.51852V7.91667C6.51852 7.0633 6.24567 6.38387 5.81194 5.90588L4.07407 7.78399ZM5.15243 5.40624L3.67538 7.00249L2.18708 5.41009C2.62949 5.68467 3.14058 5.83333 3.66667 5.83333C4.04118 5.83333 4.41202 5.75789 4.75802 5.61132C4.8948 5.55337 5.02664 5.48476 5.15243 5.40624ZM3.66667 0.833333C3.12641 0.833333 2.60828 1.05283 2.22626 1.44353C1.91252 1.76441 1.71168 2.17918 1.64992 2.62327C1.98582 2.71281 2.30946 2.71051 2.61545 2.62355C3.00665 2.51236 3.39845 2.25422 3.76326 1.81395L4.07407 1.43886L4.38488 1.81395C4.74994 2.25451 5.03707 2.40499 5.26865 2.46474C5.40528 2.5 5.52782 2.50832 5.66414 2.50809C5.63818 2.37531 5.59956 2.24511 5.54864 2.11941C5.44627 1.86665 5.29623 1.63698 5.10707 1.44353C4.91791 1.25007 4.69335 1.09661 4.44621 0.991918C4.19906 0.88722 3.93417 0.833333 3.66667 0.833333ZM5.66089 3.34156C5.477 3.33983 5.27284 3.32527 5.06931 3.27276C4.7391 3.18755 4.41161 3.01106 4.07395 2.68845C3.69899 3.04884 3.28189 3.29905 2.83362 3.42645C2.46387 3.53154 2.08497 3.54958 1.70733 3.48658C1.80144 3.82496 1.9787 4.13661 2.22626 4.38981C2.60828 4.78051 3.12641 5 3.66667 5C3.93417 5 4.19906 4.94611 4.44621 4.84142C4.69335 4.73672 4.91791 4.58326 5.10707 4.38981C5.29623 4.19635 5.44627 3.96669 5.54864 3.71392C5.59747 3.59337 5.63499 3.46869 5.66089 3.34156Z" fill="#13103A" />
                                    </svg>
                                </span>
                                <svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M5.9858 0.118168L10 3.98029L5.9858 7.84241C5.94954 7.88798 5.90349 7.92556 5.85074 7.95261C5.79799 7.97966 5.73979 7.99554 5.68007 7.99919C5.62035 8.00284 5.56051 7.99416 5.5046 7.97375C5.44869 7.95333 5.39801 7.92166 5.356 7.88087C5.31399 7.84009 5.28163 7.79113 5.26111 7.73733C5.24058 7.68353 5.23237 7.62614 5.23703 7.56904C5.24169 7.51194 5.25912 7.45646 5.28813 7.40637C5.31714 7.35628 5.35706 7.31273 5.40518 7.27869L8.40852 4.3801L0.417711 4.3801C0.306928 4.3801 0.200682 4.33797 0.122345 4.263C0.0440094 4.18802 1.71072e-07 4.08633 1.75707e-07 3.98029C1.80342e-07 3.87426 0.0440094 3.77256 0.122345 3.69758C0.200682 3.62261 0.306928 3.58048 0.417711 3.58048L8.40852 3.58048L5.40518 0.681894C5.32708 0.606609 5.28342 0.504711 5.28381 0.398617C5.2842 0.292523 5.32861 0.190923 5.40727 0.116168C5.48593 0.0414136 5.59239 -0.000372126 5.70323 2.67321e-06C5.81408 0.000377472 5.92023 0.0428832 5.99833 0.118168L5.9858 0.118168Z" fill="#13103A" />
                                </svg>
                                <span class="inline-flex items-center justify-center w-[22px] h-[22px] border border-[#0000001A] rounded-[5px]">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_339_6018)">
                                            <path d="M6.00073 5.32669C6.87702 5.32669 7.5874 4.62527 7.5874 3.76003C7.5874 2.89478 6.87702 2.19336 6.00073 2.19336C5.12444 2.19336 4.41406 2.89478 4.41406 3.76003C4.41406 4.62527 5.12444 5.32669 6.00073 5.32669Z" fill="#13103A" />
                                            <path d="M3.59464 3.91665H3.75464V3.77332C3.75561 3.35652 3.8732 2.94831 4.09411 2.59486C4.31501 2.24141 4.63042 1.95684 5.00464 1.77332C4.89385 1.51817 4.71603 1.2978 4.49005 1.1356C4.26408 0.973399 3.99839 0.875433 3.72121 0.852105C3.44403 0.828778 3.1657 0.88096 2.9158 1.00311C2.66589 1.12526 2.45373 1.31281 2.30186 1.54585C2.14999 1.7789 2.06407 2.04872 2.05323 2.32667C2.04239 2.60462 2.10704 2.88032 2.2403 3.12448C2.37356 3.36864 2.57047 3.57214 2.8101 3.71338C3.04974 3.85462 3.32315 3.92832 3.60131 3.92665L3.59464 3.91665ZM8.25464 3.75999V3.90332H8.41464C8.68909 3.90056 8.95773 3.82387 9.19227 3.68132C9.42681 3.53876 9.61858 3.33561 9.7474 3.09325C9.87621 2.8509 9.93731 2.57829 9.92426 2.30414C9.91121 2.02998 9.82449 1.76442 9.67324 1.53539C9.52198 1.30636 9.31178 1.12235 9.06476 1.00272C8.81774 0.883086 8.54303 0.832259 8.26956 0.855587C7.99609 0.878916 7.73396 0.975537 7.51077 1.13528C7.28758 1.29503 7.11159 1.51198 7.00131 1.76332C7.37565 1.94612 7.6914 2.22997 7.91288 2.58281C8.13437 2.93565 8.25274 3.3434 8.25464 3.75999ZM7.43131 5.48332C8.09401 5.61472 8.73479 5.83916 9.33464 6.14999C9.41917 6.19635 9.4957 6.256 9.56131 6.32665H11.3346V5.18665C11.335 5.14293 11.3233 5.09995 11.301 5.06235C11.2787 5.02475 11.2465 4.99397 11.208 4.97332C10.344 4.52143 9.38297 4.28688 8.40798 4.28999H8.18797C8.07245 4.75956 7.80677 5.17857 7.43131 5.48332ZM2.17798 6.97332C2.17737 6.80544 2.2227 6.6406 2.30908 6.49665C2.39545 6.35269 2.51956 6.23512 2.66798 6.15665C3.26783 5.84583 3.90861 5.62138 4.57131 5.48999C4.19766 5.18783 3.93215 4.77259 3.81464 4.30665H3.59464C2.61965 4.30355 1.65859 4.53809 0.794642 4.98999C0.756098 5.01063 0.723927 5.04142 0.701604 5.07902C0.67928 5.11661 0.667652 5.15959 0.667975 5.20332V7.33332H2.17798V6.97332ZM7.15464 8.89665H9.14131V9.36332H7.15464V8.89665Z" fill="#13103A" />
                                            <path d="M10.9371 7.08671H8.64709V6.75338C8.64709 6.66497 8.61197 6.58019 8.54946 6.51768C8.48695 6.45516 8.40216 6.42005 8.31376 6.42005C8.22535 6.42005 8.14057 6.45516 8.07805 6.51768C8.01554 6.58019 7.98042 6.66497 7.98042 6.75338V7.08671H7.33376V6.14338C6.89526 6.05091 6.44856 6.00289 6.00042 6.00005C4.94839 5.99607 3.91144 6.25016 2.98042 6.74005C2.93896 6.76156 2.90425 6.79411 2.88013 6.83411C2.85602 6.87411 2.84343 6.92 2.84376 6.96671V8.83671H5.21376V10.8667C5.21376 10.9551 5.24888 11.0399 5.31139 11.1024C5.3739 11.1649 5.45868 11.2 5.54709 11.2H10.9371C11.0255 11.2 11.1103 11.1649 11.1728 11.1024C11.2353 11.0399 11.2704 10.9551 11.2704 10.8667V7.42005C11.2704 7.33164 11.2353 7.24686 11.1728 7.18434C11.1103 7.12183 11.0255 7.08671 10.9371 7.08671ZM10.6038 10.54H5.88042V7.75338H7.98042V8.05671C7.98042 8.14512 8.01554 8.2299 8.07805 8.29241C8.14057 8.35493 8.22535 8.39005 8.31376 8.39005C8.40216 8.39005 8.48695 8.35493 8.54946 8.29241C8.61197 8.2299 8.64709 8.14512 8.64709 8.05671V7.75338H10.6038V10.54Z" fill="#13103A" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_339_6018">
                                                <rect width="12" height="12" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </div>
                            {{-- @foreach ( $log->leadService as $servicesas )
                            <span class="border border-[#0000001A] text-[13px] leading-[12px] font-[400] tracking-[-0.04em] text-[#000000] px-[10px] py-[5px] rounded-[5px] ">{{ $servicesas->service->serviceName}}</span>
                            @endforeach --}}
                        </div>
                        <div class="flex  flex-wrap lg-flex-nowrap items-center justify-between gap-[10px] mt-[20px]">
                            <div class="flex flex-wrap lg:flex-nowrap  items-center justify-between w-[100%] lg:w-[80%]">
                                <div class="flex-inline flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                    <label class="text-[15px] font-[600] text-[#000]">
                                        Stage:
                                    </label>
                                    {{ optional($log->leadTask)->serviceSatge->title ?? 'NA' }}


                                </div>
                                <div class="flex-inline items-center gap-[10px] w-[100%] lg:w-[30%] text-[14px] leading-[16px] font-[400] tracking-[-0.04em] text-[#666666] flex"><label class="text-[15px] font-[600] text-[#000]">Status:</label>
                                    @if(optional(optional($log->leadTask)->leadTaskDetails)->status === 0)
                                    Pending
                                    @elseif(optional(optional($log->leadTask)->leadTaskDetails)->status === 1)
                                    Completed
                                    @elseif(optional(optional($log->leadTask)->leadTaskDetails)->status === 2)
                                    On Hold
                                    @elseif(optional(optional($log->leadTask)->leadTaskDetails)->status === 3)
                                    Follow Up
                                    @elseif(optional(optional($log->leadTask)->leadTaskDetails)->status === null)
                                    Not Updated
                                    @else
                                    NA
                                    @endif


                                </div>
                                <div class="relative flex flex-col items-center group">
                                    <a href="#" data-rowId="{{$log->id}}" class=" viewLogDeatails flex items-center gap-[8px] text-[15px] font-[600]  text-[#000]">
                                        Action
                                    </a>
                                    <div class=" absolute bottom-0 flex flex-col items-center hidden mb-5 group-hover:flex">
                                        <span class="flex items-center justify-center relative rounded-md z-10 px-[2px]  w-[70px] h-[30px] text-xs leading-none text-white whitespace-no-wrap bg-[#13103a] shadow-lg">Logs detail</p></span>
                                        <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-[10px] w-[5%] text-[14px] leading-[16px] font-[400] tracking-[-0.04em] text-[#666666] flex">
                                <div class="relative flex flex-col pr-[10px] items-center group">
                                    <a href="#" class="flex items-center gap-[8px] text-[15px] font-[600]  text-[#000] py-[10px] px-[10px]">
                                        <i class="ri-download-2-line text-[22px]"></i>
                                    </a>
                                    <div class=" absolute bottom-[18px] flex flex-col items-center hidden mb-[15px] group-hover:flex">
                                        <span class="flex items-center justify-center relative rounded-md z-10 px-[2px]  w-[70px] h-[30px] text-xs leading-none text-white whitespace-no-wrap bg-[#13103a] shadow-lg">Download file</p></span>
                                        <div class="w-3 h-3 -mt-2 rotate-45 bg-black"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div id="assignUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[100%)] max-h-full bg-[rgba(0,0,0,0.6)] ">
    <div class="relative p-4 w-full max-w-[780px] max-h-full m-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-[20px] shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:px-5 md:py-[20px] border-b border-[#f2f2f2]">
                <h3 class="flex items-center gap-[8px] text-[24px] font-[600] leading-[17px] text-[#000]">
                    Log Review<p id="rowLeadId" class="text-sky-500"></p>

                </h3>
                <button type="button" class=" absolute top-[-10px] right-[-10px] w-[35px] h-[35px] bg-[#13103A] flex items-center justify-center text-[#fff] rounded-[60px]" data-modal-hide="assignUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>

                </button>
            </div>
            <!-- Modal body -->
            <div class="p-[20px]">
                <form method="POST" class="space-y-[20px]">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-[20px]">
                        <div class="w-full border-[1px] border-[#f2f2f2] min-h-[150px] p-[15px] rounded-[8px] text-[#000] text-[15px] leading-[22px] font-[400]">
                            <!-- Client Name -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626]">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Client Name:
                                </label>
                                <p id="rowClient" class="text-[15px] font-[500] text-[#262626] w-[60%] overflow-hidden truncate"></p>
                            </div>


                            <!-- Service -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Service:
                                </label>
                                <p id="rowService" class="w-[60%]"></p>
                            </div>

                            <!-- Stage -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Stage:
                                </label>
                                <p id="rowStage" class="w-[60%]"></p>
                            </div>

                            <!-- Assigned To -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Assigned To:
                                </label>
                                <p id="rowAssignedTo" class="w-[60%]"></p>
                            </div>

                            <!-- Status -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Status:
                                </label>
                                <p id="rowStatus" class="w-[60%]"></p>
                            </div>

                            <!-- Verified On -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Verified On:
                                </label>
                                <p id="rowVerifiedOn" class="w-[60%]"></p>
                            </div>

                            <!-- Clarification -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Clarification:
                                </label>
                                <p id="rowClarification" class="w-[60%]"></p>
                            </div>

                            <!-- Dead Line -->
                            <div class="flex flex-wrap lg:flex-nowrap items-center gap-[10px] w-[100%] lg:w-[40%] text-[15px] leading-[20px] font-[500] tracking-[-0.03em] text-[#262626] ">
                                <label class="text-[15px] font-[600] text-[#000] w-[40%]">
                                    Dead Line:
                                </label>
                                <p id="rowDeadLine" class="w-[60%]"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 md:px-5 md:py-[20px] border-b border-[#f2f2f2]">
                        <h3 class="text-[24px] font-[600] leading-[17px] text-[#000]">
                            Remark
                        </h3>
                    </div>

                    <!-- Textarea Section -->
                    <div class="flex flex-col md:flex-row gap-[20px]">
                        <div class="w-full">
                            <textarea id="remark" class="w-full border-[1px] border-[#f2f2f2] min-h-[80px] p-[15px] rounded-[8px] text-[#000] text-[15px] leading-[22px] font-[400] focus:outline-none"></textarea>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has('lead_id') || urlParams.get('lead_id') === '') {
            $('#showLog').attr('hidden', true);
        } else {
            $('#showLog').removeAttr('hidden');
        }
        $(document).on('click', '.viewLogDeatails', function(e) {
            e.preventDefault();
            $('#assignUserModal').removeClass('hidden');
        });
        $(document).on('click', '[data-modal-hide="assignUserModal"]', function() {
            $('#assignUserModal').addClass('hidden');
        });
        $(".viewLogDeatails").on('click', function() {
            var lead_id = $(this).data('rowid');

            $.ajax({
                url: "{{ route('leads.getLogs') }}",
                method: 'POST',
                data: {
                    lead_id: lead_id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == 200) {
                        console.log(response);

                        var lead = response.data[0]; // Assuming you have an array in data

                        $('#rowClient').text(lead.client_name);
                        $('#rowLeadId').text(lead.lead_id);
                        $('#rowService').text(lead.services.join(", "));
                        $('#rowStage').text(lead.stage);
                        $('#rowAssignedTo').text(lead.assignTo);
                        $('#remark').val(lead.remark);
                        $('#rowStatus').text(function() {
                            switch (lead.status) {
                                case 0:
                                    return 'Pending';
                                case 1:
                                    return 'Completed';
                                case 2:
                                    return 'Hold';
                                case 3:
                                    return 'Follow Up';
                                default:
                                    return 'Not Updated';
                            }
                        });
                        $('#rowVerifiedOn').text(lead.verifiedOn ? lead.verifiedOn : 'Not Updated');
                        $('#rowClarification').text(lead.logDescription);
                        $('#rowDeadLine').text(lead.deadLine);
                    }
                }
            })
        })


    })
</script>
@stop