@extends('layouts.default')
@section('content')
<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Lead logs </h3>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[20px] p-[23px]">
        <form id="filterForm" action="" class="w-full" method="GET">
            @csrf
            <div class="flex items-end gap-[10px] w-full">
                  <div class="w-[50%]">
                     <label class="flex text-[15px] text-[#000] mb-[5px]">Lead ID<strong class="text-[#f83434]">*</strong></label>
                     <select name="lead_id" id="lead_id" class="allform-filter-select2 !outline-none h-[40px] border border-[#0000001A] w-full md:w-[95px] rounded-[10px] p-[10px] text-[14px] font-[400] leading-[16px] text-[#13103A]" onchange="refreshContent()">
                           <option value="">Select Lead ID</option>
                           @forelse($leads as $lead)
                              <option value="{{ $lead->id }}"> {{ $lead->lead_id }} </option>
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

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] p-[23px]" id="content-block-main">
        @include('leads.logs.list', ['logs' => $logs])
    </div>
</div>
@stop

@push('footer')
   <script>
         const r = "{{ route('leads.logs') }}";
         const contentBox = $('div#content-block-main');


        var page = 1;
        $(document).on('click', '.page-item a', function (e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var lead_id = $('#lead_id').val();
            refreshContent(page, lead_id);
        });

        function genQuery(page, lead_id) {
            var q = '';
            var lead_id = $('#lead_id').val();

            if (lead_id !== '') {
                q += (q ? '&' : '?') + 'lead_id=' + lead_id;
            }

            if (page) {
                q += (q ? '&' : '?') + 'page=' + page;
            }

            return q;
        }

        function refreshContent(page, lead_id){
            var t = r;
            var q = genQuery(page, lead_id);
            var url = t + q;
            history.pushState({}, document.title, url);
            $.get(url, function(h) {
                contentBox.html(h);
                console.log('hi');
            }, 'html')
            .fail(err => {
                console.table(err);
            })
            .always(() => {
                console.log('refreshed');
            });
        }

   </script>
@endpush

