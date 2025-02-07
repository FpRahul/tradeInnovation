<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicon.ico')}}">

    <title>Trade Innovation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

    <!-- Datepicker css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"/>
    <!-- Datepicker css End-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Datepicker Js -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- Datepicker Js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#F1F2F4] ">
    <div class="flex flex-wrap h-[100vh]">
        <div id="sidebar" class="hidden xl:block bg-white shadow-lg xl:shadow-none w-[270px]  transform transition-transform duration-500 ease-in-out -translate-x-full xl:translate-x-0 fixed xl:relative top-0 left-0 z-[99] md:z-[2] border-r-[1px] border-[#0000001A] ">
            <div class="space-y-[24px] h-[100vh] lg:h-[calc(100vh-54px)] max-h-[100vh] lg:max-h-[calc(100vh-54px)] px-[18px] md:px-[25px] py-[14px] overflow-y-auto ">
                @include('layouts.sidebar')
            </div>
        </div>
        <div class="w-full xl:w-[calc(100%-270px)]">
            <div class="bg-[#F1F2F4] admin-header sticky top-0 z-[9] px-[15px] md:px-[30px] py-[15px] ">
                @include('layouts.header')
            </div>
            <div class="main-right-content xl:h-[calc(100vh-153px)] xl:max-h-[calc(100vh-129px)] overflow-y-auto py-[23px] pt-[14px] overflow-hidden px-[15px] md:px-[30px]">
                @yield('content')
            </div>
        </div>
        <div class="w-full mt-[auto] bg-[#13103A] text-center h-[54px] flex items-center justify-center ">
            @include('layouts.footer')
        </div>
    </div>
    <script>
        jQuery(document).on('click', '#toggle_btn', function() {
            if (jQuery('#sidebar').hasClass('hidden')) {
                jQuery('#sidebar').removeClass('hidden');
                jQuery('#sidebar').removeClass('-translate-x-full');
            } else {
                jQuery('#sidebar').addClass('hidden');
                jQuery('#sidebar').addClass('-translate-x-full');
            }
        });

        jQuery(document).click(function(e) {
            e.stopPropagation();
            var container = jQuery("#sidebar");
            var container2 = jQuery("#toggle_btn");

            //check if the clicked area is dropDown or not
            if (container.has(e.target).length === 0 && container2.has(e.target).length === 0) {
                jQuery('#sidebar').addClass('hidden');
                jQuery('#sidebar').addClass('-translate-x-full');
            }
        })

        $(document).ready(function() {
            $('.select2').select2({
                tags: true,
                multiple: true
            });
            $('.allform-filter-select2').select2({
                placeholder: 'Select'
            });
            $('.allform-select2').select2({
                placeholder: 'Select',
                allowClear: true
            });
        })
        $(document).ready(function() {
            $('.userExperienceRepeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        var deleteId = $(this).find('.deleteRepeaterRow').data('id');
                        if(deleteId > 0){
                            $.ajax({
                                method: 'POST',
                                url: "{{ route('users.deleterepeater') }}",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    id: deleteId
                                },
                                success: function(res) {
                                    if (res == 1) {
                                        $(this).slideUp(deleteElement);
                                    }
                                },
                                error: function(err) {
                                    alert(err);
                                }
                            })
                        }else{
                            $(this).slideUp(deleteElement);
                        }
                        

                    }
                },
                isFirstItemUndeletable: true
            })
        });

        $(document).ready(function() {
            $('.subservicerepeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        var deleteId = $(this).find('.deleteRepeaterRow').data('id');
                        if(deleteId > 0){
                            $.ajax({
                                method: 'POST',
                                url: "{{ route('subservice.deleterepeater') }}",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    id: deleteId
                                },
                                success: function(res) {
                                    if (res == 1) {
                                        $(this).slideUp(deleteElement);
                                    }
                                },
                                error: function(err) {
                                    alert(err);
                                }
                            })
                        }else{
                            $(this).slideUp(deleteElement);
                        }

                    }
                },
                isFirstItemUndeletable: true
            })
        });

        $(document).ready(function() {
            $('.leadServiceRepeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        var deleteId = $(this).find('.deleteLeadRepeaterRow').data('id');
                        if(deleteId > 0){
                            // $.ajax({
                            //     method: 'POST',
                            //     url: "{{ route('lead.deleterepeater') }}",
                            //     headers: {
                            //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            //     },
                            //     data: {
                            //         id: deleteId
                            //     },
                            //     success: function(res) {
                            //         if (res == 1) {
                            //             $(this).slideUp(deleteElement);
                            //         }
                            //     },
                            //     error: function(err) {
                            //         alert(err);
                            //     }
                            // })
                        }else{
                            $(this).slideUp(deleteElement);
                        }
                    }
                },
                isFirstItemUndeletable: true
            })
        });

        $(document).ready(function() {
            $('.leadAttachmentRepeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'image': ''
                },
                show: function() {                    
                    $(this).find('img').attr('src','/assets/images/noimage.png');
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        var deleteId = $(this).find('.deleteAttachmentRepeaterRow').data('id');
                        if(deleteId > 0){
                            $.ajax({
                                method: 'POST',
                                url: "{{ route('lead.deleteattachmentrepeater') }}",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    id: deleteId
                                },
                                success: function(res) {
                                    if (res == 1) {
                                        $(this).slideUp(deleteElement);
                                    }
                                },
                                error: function(err) {
                                    alert(err);
                                }
                            })
                        }else{
                            $(this).slideUp(deleteElement);
                        }

                    }
                },
                isFirstItemUndeletable: true
            })
        });

        jQuery(document).ready(function() {
            jQuery('#summernote').summernote({
                placeholder: 'Message here..',
                tabsize: 2,
                height: 160,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        })

        jQuery(document).on('click', '.dropdown .button', function() {
            jQuery(this).parent().find('.dropdown_menu').toggleClass('hidden');
        });

        jQuery(document).on('click', 'table .dropdown .button', function() {
            const $menu = jQuery(this).parent().find('.dropdown_menus');
            let dataId = jQuery(this).attr('data-id');
            if (!dataId) {
                let random = Math.floor(Math.random() * 10);
                jQuery(this).attr('data-id', random);
                jQuery($menu).attr('data-id', random);
            }
            if ($menu.hasClass('hidden')) {

                const offset = jQuery(this).offset();
                $menu.appendTo('body').css({
                    position: 'absolute',
                    top: offset.top + jQuery(this).outerHeight(),
                    left: offset.left - 85,
                    zIndex: 9999,
                }).removeClass('hidden');
            } else {
                let ele = jQuery(`.dropdown_menus[data-id="${dataId}"]`);
                console.log('55555: ', ele);
                jQuery(ele).addClass('hidden');
                jQuery(this).parent().append(ele);
            }
        });

        jQuery(document).click(function(e) {
            e.stopPropagation();
            var container = jQuery("table .dropdown");

            //check if the clicked area is dropDown or not
            if (container.has(e.target).length === 0) {
                jQuery('.dropdown_menus').each(function() {
                    const dataId = jQuery(this).attr('data-id');
                    let ele = jQuery(`table .dropdown .button[data-id="${dataId}"]`);
                    jQuery(this).addClass('hidden');
                    jQuery(ele).parent().append(jQuery(this));
                });
            }
        });

        jQuery(document).click(function(e) {
            e.stopPropagation();
            var container = jQuery(".dropdown");

            //check if the clicked area is dropDown or not
            if (container.has(e.target).length === 0) {
                jQuery('.dropdown_menu').addClass('hidden');
            }
        });

        jQuery(document).on('click', '.accordian .item>a', function() {

            if (!jQuery(this).hasClass('nav-link')) {
                jQuery(this).parent().children('.accordian_body').slideDown();
                jQuery(this).parent().siblings().children('.accordian_body').slideUp();
                let tabId = jQuery(this).attr('data-id');
                
                jQuery(`#${tabId}`).removeClass('hidden');
                jQuery(`#${tabId}`).siblings().addClass('hidden');
            } else {
                jQuery(this).parent().children('.accordian_body').slideToggle();
                jQuery(this).parent('.has-sub-submenu').toggleClass('open');
                jQuery(this).parent().siblings().children('.accordian_body').slideUp();
                jQuery(this).parent('.has-sub-submenu').siblings().removeClass('open');
            }
        })


        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif


        // Lead Tabbing

        const tabs = document.querySelectorAll(".tabs");
        const tab = document.querySelectorAll(".tab");
        const panel = document.querySelectorAll(".tab-content");

        function onTabClick(event) {

        // deactivate existing active tabs and panel

        for (let i = 0; i < tab.length; i++) {
        tab[i].classList.remove("active");
        }

        for (let i = 0; i < panel.length; i++) {
        panel[i].classList.remove("active");
        }


        // activate new tabs and panel
        event.target.classList.add('active');
        let classString = event.target.getAttribute('data-target');
        console.log(classString);
        document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active");
        }

        for (let i = 0; i < tab.length; i++) {
        tab[i].addEventListener('click', onTabClick, false);
        }



    </script>

    @stack('footer')
      
</body>

</html>