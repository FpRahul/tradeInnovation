<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-black dark:text-white/50">
    <div class="flex flex-wrap gap-x-[30px] h-[100vh]">
        <div class="w-[210px] py-[14px] px-[18px] space-y-[24px] h-[calc(100vh-54px)] max-h-[calc(100vh-54px)] overflow-y-auto">
            @include('layouts.sidebar')
        </div>
        <div class="w-[calc(100%-270px)]">
            <div class="admin-header pl-[30px]">
                @include('layouts.header')
            </div>
            <div class="main-right-content pr-[22px] pt-[23px] h-[calc(100vh-129px)] max-h-[calc(100vh-129px)] overflow-y-auto pl-[30px]">
                @yield('content')
            </div>
        </div>
        <div class="w-full bg-[#13103A] text-center h-[54px] flex items-center justify-center ">
            @include('layouts.footer')
        </div>
    </div>
</body>

</html>