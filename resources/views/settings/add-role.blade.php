@extends('layouts.default')
@section('content')
@php
use App\Models\MenuAction;
@endphp
<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Create Role</h3>
    </div>
    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">
        <form method="POST">
            @csrf

            <div class="py-[25px] px-[20px]">
                <label for="rolename" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Role Name</label>
                <input type="text" required name="rolename" id="rolename" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[18px] py-[14px] rounded-[10px] !outline-none" placeholder="Enter Role Name" value="{{ $roleData->name }}">
            
                @error('rolename')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="name" value="{{ $roleData->name }}">
            <div class="px-[20px] flex flex-col md:flex-row gap-[30px]">
                <div class="w-[100%] md:w-[230px] max-h-[100%] overflow-y-auto ">
                    <ul class="accordian createRoll">
                    @foreach($serializeMenus as $menuKey => $menu)
                        <li class="item border-b-[1px] border-b-[#E8E7FF] py-[10px] active">
                            <a href="javascript:void(0)" data-id="tab{{ $menuKey }}" class="text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center justify-between px-[0] py-[5px]  ">
                                    @if($menuKey==1)
                                        @php $isChecked = 'disabled checked';  @endphp
                                    @else
                                        @if (isset($menuAddedAction[$menuKey]))
                                            @php $isChecked = 'checked';  @endphp
                                        @else
                                            @php $isChecked = '';  @endphp
                                        @endif
                                    @endif


                                    <div class="inline-flex items-center">
                                        <label class="flex justify-start gap-[8px] items-center cursor-pointer relative">
                                            <input type="checkbox" checked class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a] parent-element"  m-id="{{ $menuKey }}" id="main-menu-id-{{ $menuKey }}" name="permission[mainMenu][{{ $menuKey }}]" {{ $isChecked }}>
                                        
                                            <span class="h-5 w-5 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[13px] left-[13px] h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>

                                            {{ $menu['menu']['name'] }}
                                            
                                        </label>
                                    </div>
                            
                                @if (isset($menu['subMenu']))
                                    <svg width="6" height="12" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.451987 1.57999L1.51299 0.519991L7.29199 6.29699C7.38514 6.38956 7.45907 6.49963 7.50952 6.62088C7.55997 6.74213 7.58594 6.87216 7.58594 7.00349C7.58594 7.13482 7.55997 7.26485 7.50952 7.3861C7.45907 7.50735 7.38514 7.61742 7.29199 7.70999L1.51299 13.49L0.452987 12.43L5.87699 7.00499L0.451987 1.57999Z" fill="#999" />
                                    </svg>
                                @endif
                            </a>
                            @if (isset($menu['subMenu']))
                            <ul class="accordian_body pt-[5px]">
                                @foreach($menu['subMenu'] as $smid => $subMenu)
                                @php if($smid==9){ continue;} @endphp
                                @if (isset($menuAddedAction[$smid]))
                                    @php $isChecked = 'checked';  @endphp
                                @else
                                    @php $isChecked = '';  @endphp
                                @endif
                                <li>


                               


                                    <a href="javascript:void(0)" class="text-[13px] font-[400] leading-[16px] text-[#000000] flex items-center justify-between px-[6px] py-[5px] pl-[20px]">
                                        <!-- <span class="inline-flex items-center gap-[7px] ">
                                            <input type="checkbox" name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $smid }}]" id="sub-menu-id-{{ $smid }}" subid="{{ $smid }}" class="peer w-[15px] h-[15px] parent-sub-element" {{ $isChecked }}>

                                            <label for="sub-menu-id-{{ $smid }}">{{ $subMenu['name'] }}</label>


                                        </span>
                                     -->
                                        <div class="inline-flex items-center">
                                            <label class="flex justify-start gap-[8px] items-center cursor-pointer relative">
                                                <input type="checkbox" checked class="peer h-4 w-4 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a] parent-sub-element"  name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $smid }}]" id="sub-menu-id-{{ $smid }}" subid="{{ $smid }}"   {{ $isChecked }}>
                                            
                                                <span class="h-4 w-4 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[11px] left-[11px] h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>

                                                {{ $subMenu['name'] }}
                                                
                                            </label>
                                        </div>




                                        @if(isset($menu['subSubMenu'][$smid]))
                                        <svg width="6" height="12" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.451987 1.57999L1.51299 0.519991L7.29199 6.29699C7.38514 6.38956 7.45907 6.49963 7.50952 6.62088C7.55997 6.74213 7.58594 6.87216 7.58594 7.00349C7.58594 7.13482 7.55997 7.26485 7.50952 7.3861C7.45907 7.50735 7.38514 7.61742 7.29199 7.70999L1.51299 13.49L0.452987 12.43L5.87699 7.00499L0.451987 1.57999Z" fill="#999" />
                                        </svg>
                                        @endif
                                    </a>
                                    @if(isset($menu['subSubMenu'][$smid]))
                                    <ul class="accordian_body pl-[17px] pt-[7px] pb-[10px]">
                                        @foreach($menu['subSubMenu'][$smid] as $ssubmid => $subSubMenu)
                                        @if (isset($menuAddedAction[$ssubmid]))
                                            @php $isChecked = 'checked';  @endphp
                                        @else
                                            @php $isChecked = '';  @endphp
                                        @endif
                                        <li class="item">
                                            <a href="javascript:void(0)" data-id="tab{{ $smid }}" class="text-[12px] font-[400] leading-[16px] text-[#000000] flex items-center justify-between  px-[6px] py-[5px] pl-[20px] ">
                                                <div class="inline-flex items-center gap-[7px] ">
                                                    <label class="flex justify-start gap-[8px] items-center cursor-pointer relative">
                                                        <input type="checkbox" class="peer h-4 w-4 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a] parent-sub-sub-element"  m-id="{{ $menuKey }}" id="sub-sub-menu-id-{{ $ssubmid }}" name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $smid }}][subSubMenu][{{ $ssubmid }}]" {{ $isChecked }}>

                                                        <span
                                                            class="h-4 w-4 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[11px] left-[11px] h-2.5 w-2.5" viewBox="0 0 20 20"
                                                                fill="currentColor" stroke="currentColor" stroke-width="1">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>

                                                        {{ $subSubMenu['name'] }}

                                                    </label>





                                                </div>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    @endforeach
                    </ul>
                </div>
                <div class="w-[100%] md:w-[calc(100%-260px)]">
                @foreach($serializeMenus as $menuKey => $menu)
                    <div id="tab{{ $menuKey }}" class="hidden">
                    @if (isset($menu['subMenu']))
                    
                    <h2 class="aa font-semibold text-[20px] mb-[7px] main-heading-itm">{{ $menu['menu']['name'] }}</h2>
                        <div class="flex flex-col items-start w-full gap-[20px]">
                            @foreach($menu['subMenu'] as $subMenuKey => $subMenu)
                            @php $menuAction = MenuAction::where('menuId',$subMenuKey)->get(); @endphp
                            
                            <div class="w-full flex flex-col gap-[10px] bxshadow p-[15px] rounded-[10px]">
                                <h2 class="font-semibold text-[15px]">{{ $subMenu['name'] }} </h2>
                                @if(isset($menu['subSubMenu'][$subMenuKey]))
                                @foreach ($menu['subSubMenu'][$subMenuKey] as $subsKey =>$subsVal)
                                <div class="mb-[10px]">
                                    <h4 class="font-semibold text-[#242323] text-[14px] mb-[7px]">{{ $subsVal['name'] }}</h4>
                                    <div class=" flex flex-wrap gap-[10px]" id="actions-of-{{ $subsKey }}">
                                        @php $menuSubAction = MenuAction::where('menuId',$subsKey)->get(); @endphp
                                        @foreach($menuSubAction as $acKey =>$acVal)
                                        @if (isset($menuAddedAction[$subsKey]) && in_array($acVal->id,$menuAddedAction[$subsKey]))
                                            @php $isChecked = 'checked';  @endphp
                                        @else
                                            @php $isChecked = '';  @endphp
                                        @endif

                                       


                                        <label class="flex justify-start gap-[8px] items-center cursor-pointer relative bg-[#fff] px-[10px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] text-[13px]">
                                            <input type="checkbox"  name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $subMenuKey }}][subSubMenu][{{ $subsKey }}][action][{{ $acVal->id }}]"
                                                class="peer h-4 w-4 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a]  sub-sub-menu-actions"
                                                sub-menu-id="{{ $subMenuKey }}" sub-sub-menu-id="{{ $subsKey }}" menu-id="{{ $menuKey }}" {{ $isChecked }}>

                                            <span
                                                class="h-4 w-4 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[11px] left-[11px] h-2.5 w-2.5" viewBox="0 0 20 20"
                                                    fill="currentColor" stroke="currentColor" stroke-width="1">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        
                                            {{ $acVal->actionName }}

                                        </label>      









                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                <div class="flex flex-wrap gap-[10px]" id="actions-of-{{ $subMenuKey }}">
                                    @foreach($menuAction as $acKey =>$acVal)
                                    @if (isset($menuAddedAction[$subMenuKey]) && in_array($acVal->id,$menuAddedAction[$subMenuKey]))
                                        @php $isChecked = 'checked';  @endphp
                                    @else
                                        @php $isChecked = '';  @endphp
                                    @endif



                                    <!-- <label class="border-[1px] border-[#0000001A] rounded-[10px] text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center gap-[7px] py-[5px] px-[10px] ">
                                        <input type="checkbox" name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $subMenuKey }}][action][{{ $acVal->id }}]" class="w-[15px] h-[15px] sub-menu-actions" sub-menu-id="{{ $subMenuKey }}" menu-id="{{ $menuKey }}" {{ $isChecked }}>
                                        {{ $acVal->actionName }}
                                    </label> -->


                                    <label class="flex justify-start gap-[8px] items-center cursor-pointer relative bg-[#fff] px-[10px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] text-[13px]">
                                        <input type="checkbox" 
                                            class="peer h-4 w-4 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a] sub-menu-actions"  name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $subMenuKey }}][action][{{ $acVal->id }}]"  sub-menu-id="{{ $subMenuKey }}" menu-id="{{ $menuKey }}" {{ $isChecked }}>

                                        <span
                                            class="h-4 w-4 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[11px] left-[11px] h-2.5 w-2.5" viewBox="0 0 20 20"
                                                fill="currentColor" stroke="currentColor" stroke-width="1">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>

                                        {{ $acVal->actionName }}

                                    </label>





                                    @endforeach
                                </div>
                                @else
                                <div class=" flex flex-wrap gap-[10px]" id="actions-of-{{ $subMenuKey }}">
                                    @foreach($menuAction as $acKey =>$acVal)
                                    @if (isset($menuAddedAction[$subMenuKey]) && in_array($acVal->id,$menuAddedAction[$subMenuKey]))
                                        @php $isChecked = 'checked';  @endphp
                                    @else
                                        @php $isChecked = '';  @endphp
                                    @endif








                                    <!-- <label class="border-[1px] border-[#0000001A] rounded-[10px] text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center gap-[7px] py-[5px] px-[10px] ">
                                        <input type="checkbox" name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $subMenuKey }}][action][{{ $acVal->id }}]" class="w-[15px] h-[15px] sub-menu-actions" sub-menu-id="{{ $subMenuKey }}" menu-id="{{ $menuKey }}" {{ $isChecked }}>
                                        {{ $acVal->actionName }}
                                    </label> -->



                                    <label class="flex justify-start gap-[8px] items-center cursor-pointer relative bg-[#fff] px-[10px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] text-[13px]">
                                        <input type="checkbox" 
                                            class="peer h-4 w-4 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a] sub-menu-actions"   name="permission[mainMenu][{{ $menuKey }}][subMenu][{{ $subMenuKey }}][action][{{ $acVal->id }}]" sub-menu-id="{{ $subMenuKey }}" menu-id="{{ $menuKey }}" {{ $isChecked }}>

                                        <span
                                            class="h-4 w-4 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[11px] left-[11px] h-2.5 w-2.5" viewBox="0 0 20 20"
                                                fill="currentColor" stroke="currentColor" stroke-width="1">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>

                                        {{ $acVal->actionName }}

                                    </label>








                                    @endforeach
                                </div>
                                @endif
                                
                            </div>
                            @endforeach
                        </div>

                        
                    @else
                        @php $menuAction = MenuAction::where('menuId',$menuKey)->get(); @endphp
                        <div class="mt-3">
                            <h4 class="font-semibold text-[18px] mb-[7px] main-heading-itm">{{ $menu['menu']['name'] }}</h4>
                            <div class="flex flex-wrap gap-[10px]">
                                @foreach($menuAction as $acKey =>$acVal)
                                @if (isset($menuAddedAction[$menuKey]) && in_array($acVal->id,$menuAddedAction[$menuKey]))
                                    @php $isChecked = 'checked';  @endphp
                                @else
                                    @php $isChecked = '';  @endphp
                                @endif

                                <!-- <label class="border-[1px] border-[#0000001A] rounded-[10px] text-[14px] font-[400] leading-[16px] text-[#000000] flex items-center gap-[7px] py-[5px] px-[10px] ">
                                    {{-- <input type="checkbox" name="permission[action][{{ $menuKey }}][{{ $subMenuKey }}][{{ $acVal->id }}]" class="w-[15px] h-[15px]"> --}}
                                    <input type="checkbox" name="permission[mainMenu][{{ $menuKey }}][action][{{ $acVal->id }}]" class="w-[15px] h-[15px] main-menu-actions" menu-id="{{ $menuKey }}" {{ $isChecked }}>
                                    {{ $acVal->actionName }} Mukesh
                                </label> -->

                                <label
                                    class="flex justify-start gap-[8px] items-center cursor-pointer relative bg-[#fff] px-[10px] py-[8px] rounded-[5px] border-[1px] border-[#ccc] text-[13px]">
                                    <input type="checkbox"
                                        class="peer h-4 w-4 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[#13103a] checked:border-[#13103a] main-menu-actions"  name="permission[mainMenu][{{ $menuKey }}][action][{{ $acVal->id }}]" menu-id="{{ $menuKey }}" {{ $isChecked }}>

                                    <span
                                        class="h-4 w-4 absolute  text-white opacity-0 peer-checked:opacity-100 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-[11px] left-[11px] h-2.5 w-2.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>

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
            <div class="py-[30px] px-[20px] flex items-center justify-end ">
                <button class=" inline-flex items-center gap-[10px] text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px] ">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        var url = window.location.href; 
        var id = url.split('/').pop();
        id = parseInt(id)
        if(isNaN(id)){
            $("#rolename").prop('disabled', false)
        }else{  
            $("#rolename").prop('disabled', true)

        }     
    })
    $(document).on('click','.parent-element',function (){
        if($(this).is(':checked')){
            $(this).parent().parent().parent().parent().find('input:checkbox').prop('checked',true);
        }else{
            $(this).parent().parent().parent().parent().find('input:checkbox').prop('checked',false);
            $('#tab'+$(this).attr('m-id')).find('input:checkbox').prop('checked',false);
        }
    })

    $(document).on('click','.parent-sub-element',function (){
        if($(this).is(':checked')){
            $(this).parent().parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',true);
            if($(this).parent().parent().parent().parent().find('ul').length>0){
                $(this).parent().parent().parent().parent().find('ul').find('input:checkbox.parent-sub-sub-element').prop('checked',true);
            }
        }else{
            if($(this).parent().parent().parent().parent().parent().find('input:checkbox:checked').length>0){
                $(this).parent().parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',false);
                if($(this).parent().parent().parent().parent().find('ul').length>0){
                    $(this).parent().parent().parent().parent().find('ul').find('input:checkbox.parent-sub-sub-element').prop('checked',false);
                }
            }else{
                $(this).parent().parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',false);
            }
            $('#actions-of-'+$(this).attr('subid')).find('input:checkbox').prop('checked',false);
        }
    });

    $(document).on('click','.parent-sub-sub-element',function (){
        if($(this).is(':checked')){
            $(this).parent().parent().parent().parent().parent().find('input:checkbox.parent-sub-element').prop('checked',true);
            $(this).parent().parent().parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',true);
        }else{
            if($(this).parent().parent().parent().parent().find('input:checkbox:checked').length>0){

            }else{
                $(this).parent().parent().parent().parent().parent().find('input:checkbox.parent-sub-element').prop('checked',false);
                // $(this).parent().parent().parent().parent().parent().parent().parent().find('input:checkbox.parent-element').prop('checked',false);
            }
            $('#actions-of-'+$(this).attr('subid')).find('input:checkbox').prop('checked',false);
        }
    });

    $(document).on('click','.sub-menu-actions',function(){
        if($(this).is(':checked')){
            if ($('#sub-menu-id-'+$(this).attr('sub-menu-id')).length) {
                if($('#sub-menu-id-'+$(this).attr('sub-menu-id')).is(':checked')){

                }else{
                    $('#sub-menu-id-'+$(this).attr('sub-menu-id')).trigger('click');
                }
            }
        }else{
            if($(this).parent().parent().find('input:checkbox:checked').length>0){

            }else{
                if($('#sub-menu-id-'+$(this).attr('sub-menu-id')).is(':checked')){
                    $('#sub-menu-id-'+$(this).attr('sub-menu-id')).trigger('click');
                }
            }
        }
    });

    $(document).on('click','.sub-sub-menu-actions',function(){
        if($(this).is(':checked')){
            if ($('#sub-sub-menu-id-'+$(this).attr('sub-sub-menu-id')).length) {
                if($('#sub-sub-menu-id-'+$(this).attr('sub-sub-menu-id')).is(':checked')){

                }else{
                    $('#sub-sub-menu-id-'+$(this).attr('sub-sub-menu-id')).trigger('click');
                }
            }
        }else{
            if($(this).parent().parent().find('input:checkbox:checked').length>0){

            }else{
                if($('#sub-sub-menu-id-'+$(this).attr('sub-sub-menu-id')).is(':checked')){
                    $('#sub-sub-menu-id-'+$(this).attr('sub-sub-menu-id')).trigger('click');
                }
            }
        }
    });

    $(document).on('click','.main-menu-actions',function(){
        if($(this).is(':checked')){
            if($('#main-menu-id-'+$(this).attr('menu-id')).is(':checked')){

            }else{
                $('#main-menu-id-'+$(this).attr('menu-id')).trigger('click');
            }
        }else{
            if($(this).parent().parent().find('input:checkbox:checked').length>0){

            }else{
                if($('#main-menu-id-'+$(this).attr('menu-id')).is(':checked')){
                    $('#main-menu-id-'+$(this).attr('menu-id')).trigger('click');
                }
            }
        }
    })
</script>

@endsection