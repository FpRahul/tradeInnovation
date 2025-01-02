<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Trade Innovation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-white dark:bg-black dark:text-white/50">
    <div class="flex flex-wrap gap-x-[40px] h-[100vh]">
        <div class="w-[230px] py-[14px] px-[18px] space-y-[24px] h-[calc(100vh-54px)] max-h-[calc(100vh-54px)] overflow-y-auto">
            @include('layouts.sidebar')
        </div>
        <div class="w-[calc(100%-270px)]">
            <div class="admin-header pl-[30px]">
                @include('layouts.header')
            </div>
            <div class="main-right-content pr-[22px] py-[23px] h-[calc(100vh-129px)] max-h-[calc(100vh-129px)] overflow-y-auto pl-[30px]">
                @yield('content')
            </div>
        </div>
        <div class="w-full bg-[#13103A] text-center h-[54px] flex items-center justify-center ">
            @include('layouts.footer')
        </div>
    </div>
    <script>
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
                jQuery(this).parent().find('.accordian_body').slideDown();
                jQuery(this).parent().siblings().find('.accordian_body').slideUp();
                let tabId = jQuery(this).attr('data-id');
                console.log(tabId);
                jQuery(`#${tabId}`).removeClass('hidden');
                jQuery(`#${tabId}`).siblings().addClass('hidden');
            } else {
                jQuery(this).parent().find('.accordian_body').slideToggle();
                jQuery(this).parent().siblings().find('.accordian_body').slideUp();
            }

        })
    </script>
</body>

</html>