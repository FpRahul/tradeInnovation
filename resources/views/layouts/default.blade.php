<div class="flex flex-wrap gap-x-[60px] ">
    <div class="w-[190px]">
        @include('layouts.sidebar')
    </div>
    <div class="w-[calc(100%-250px)]">
        <div class="admin-header">
            @include('layouts.header')
        </div>
        @yield('content')
    </div>
    <div class="w-full bg-[#13103A] text-center">
        @include('layouts.footer')
    </div>
</div>