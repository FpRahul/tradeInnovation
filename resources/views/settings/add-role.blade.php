@extends('layouts.default')
@section('content')
@php
use App\Models\MenuAction;
@endphp
<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">{{$moduleName}}</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] rounded-[20px] mb-[30px]">
        <form>
            <div class="py-[25px] px-[20px]">
                <label for="rolename" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Role Name</label>
                <input type="text" name="rolename" id="rolename" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Role Name">
            </div>
            <div class="px-[20px] flex flex-col md:flex-row gap-[30px]">
                <div class="w-[230px] max-h-[450px] overflow-y-auto ">
                    <ul class="accordian space-y-[7px]">
                    @foreach($serializeMenus as $menuKey => $menu)
                        <li class="item">
                            <a href="javascript:void(0)" data-id="tab{{ $menuKey }}" class="text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center justify-between p-[15px] bg-[#f7f7f7] ">
                                <span class="inline-flex items-center gap-[7px] ">
                                    <input type="checkbox" class="w-[15px] h-[15px] parent-element" @if($menuKey==1) {{ 'disabled checked'; }} @endif>
                                    {{ $menu['menu']['name'] }}
                                </span>
                                @if (isset($menu['subMenu']))
                                    <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.451987 1.57999L1.51299 0.519991L7.29199 6.29699C7.38514 6.38956 7.45907 6.49963 7.50952 6.62088C7.55997 6.74213 7.58594 6.87216 7.58594 7.00349C7.58594 7.13482 7.55997 7.26485 7.50952 7.3861C7.45907 7.50735 7.38514 7.61742 7.29199 7.70999L1.51299 13.49L0.452987 12.43L5.87699 7.00499L0.451987 1.57999Z" fill="#000000" />
                                    </svg>
                                @endif
                            </a>
                            @if (isset($menu['subMenu']))
                            <ul class="accordian_body pl-[20px]">
                                @foreach($menu['subMenu'] as $smid => $subMenu)
                                <li>
                                    <a href="javascript:void(0)" class="text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center justify-between p-[10px] ">
                                        <span class="inline-flex items-center gap-[7px] ">
                                            <input type="checkbox" name="" id="sub-menu-id-{{ $smid }}" class="w-[15px] h-[15px] parent-sub-element">
                                            {{ $subMenu['name'] }}
                                        </span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    @endforeach
                    </ul>
                </div>
                <div class="w-[calc(100%-260px)]">
                @foreach($serializeMenus as $menuKey => $menu)
                    <div id="tab{{ $menuKey }}" class="space-y-[30px]">
                    @if (isset($menu['subMenu']))
                        @foreach($menu['subMenu'] as $subMenuKey => $subMenu)
                        @php $menuAction = MenuAction::where('menuId',$subMenuKey)->get(); @endphp
                        <div>
                            <h4 class="font-semibold text-[15px] mb-[7px]">{{ $subMenu['name'] }}</h4>
                            <div class="flex flex-wrap gap-[10px]">
                                @foreach($menuAction as $acKey =>$acVal)
                                <label class="border-[1px] border-[#0000001A] rounded-[10px] text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center gap-[7px] py-[5px] px-[10px] ">
                                    <input type="checkbox" class="w-[15px] h-[15px] sub-menu-actions" sub-menu-id="{{ $subMenuKey }}" menu-id="{{ $menuKey }}">
                                    {{ $acVal->actionName }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        @php $menuAction = MenuAction::where('menuId',$menuKey)->get(); @endphp
                        <div>
                            <h4 class="font-semibold text-[15px] mb-[7px]">{{ $menu['menu']['name'] }}</h4>
                            <div class="flex flex-wrap gap-[10px]">
                                @foreach($menuAction as $acKey =>$acVal)
                                <label class="border-[1px] border-[#0000001A] rounded-[10px] text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center gap-[7px] py-[5px] px-[10px] ">
                                    <input type="checkbox" class="w-[15px] h-[15px]">
                                    {{ $acVal->actionName }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    </div>
                @endforeach
                </div>
            </div>
            <div class="py-[30px] px-[20px] ">
                <button class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('click','.parent-element',function (){
        if($(this).is(':checked')){
            $(this).parent().parent().parent().find('input:checkbox').prop('checked',true);
        }else{
            $(this).parent().parent().parent().find('input:checkbox').prop('checked',false);
        }
    })

    $(document).on('click','.parent-sub-element',function (){
        if($(this).is(':checked')){
            $(this).parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',true);
        }else{
            if($(this).parent().parent().parent().parent().find('input:checkbox:checked').length>0){

            }else{
                $(this).parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',false);
            }
        }
    });

    $(document).on('click','.sub-menu-actions',function(){
        if($(this).is(':checked')){
            if($('#sub-menu-id-'+$(this).attr('sub-menu-id')).is(':checked')){

            }else{
                $('#sub-menu-id-'+$(this).attr('sub-menu-id')).trigger('click');
            }
        }else{
            if($(this).parent().parent().find('input:checkbox:checked').length>0){

            }else{
                $('#sub-menu-id-'+$(this).attr('sub-menu-id')).trigger('click');
            }
        }
        //sub-menu-id-
    })
</script>

@endsection