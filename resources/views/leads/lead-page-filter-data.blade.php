<table width="100%" cellpadding="0" cellspacing="0" class="min-w-[900px]">
    <thead>
        <tr>
            <th class="text-start w-[120px] bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] pl-[25px] uppercase">
                Lead id
            </th>
            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Source
            </th>
            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Client name
            </th>
            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                status
            </th>
            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Created
            </th>
            <th class="text-start bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Services
            </th>
            <th class="text-center bg-[#D9D9D933] text-[14px] font-[500] leading-[16px] text-[#000000] py-[15px] px-[15px] uppercase">
                Action
            </th>
        </tr> 
    </thead>
    <tbody>
        @if ($leadList && $leadList->isNotEmpty())
           @foreach ($leadList as $leadData)   
                            
           <tr>
            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px] pl-[25px]">
                {{$leadData->id}}
            </td>
            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                <span class="inline-flex items-center gap-[10px]"> 
                @if ($leadData->source > 0)
                     {{ getSourceData($leadData->source)->name }}
                @endif
                    <span><img src="{{ asset('assets/images/i-icon.png') }}" alt="icon"></span></span>
            </td>
            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                {{$leadData->client_name}}
            </td>
            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">                           
                <select class="px-[15px] py-[10px] pr-[20px] rounded-[3px] bg-[#fff] border-[1px] border-[#ccc] outline-none">
                    <option>Hello</option>
                    <option>Hello 2</option>
                </select>
            </td>
            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                {{ date('d M Y H:i:A', strtotime($leadData->created_at) ) }}
            </td>
            <td class="border-b-[1px] border-[#0000001A] text-start text-[14px] font-[400] leading-[16px] text-[#6F6F6F] py-[12px] px-[15px]">
                @if (!empty($leadData->leadService))
                    {{ implode(', ', collect(getServiceData($leadData->leadService))->pluck('serviceName')->toArray()) }}                           
                @endif                               
            </td>
            <td class="text-center border-b-[1px] border-[#0000001A] py-[12px] px-[15px]">
                {{-- @if (in_array('leads.add',$permissionDetails['accessableRoutes']) || )
                    
                @endif --}}
                <div class="dropdown inline-block relative ml-[auto] mr-[20px] ">
                    <a href="javascript:void(0)" type="button" class="button">
                        <svg width="18" height="4" viewBox="0 0 18 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z" fill="black" />
                        </svg>
                    </a>
                    <div class="dropdown_menus absolute right-0 z-10 mt-2 w-[100px] origin-top-right rounded-md bg-white shadow-md ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="text-start" role="none">
                            <a href="{{ route('leads.add')}}/{{$leadData->id}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Edit</a>
                            {{-- <a href="javascript:void(0)" data-id="{{$leadData->id}}" class="lead_assign_to_user block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700" data-modal-target="assignUserModal" data-modal-toggle="assignUserModal" type="button">Assign</a> --}}
                            <a href="#" data-id="{{$leadData->id}}" class="lead_archive block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Archive</a>
                            <a href="{{ route('leads.quote')}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Send Quote</a>
                            <a href="{{ route('leads.logs')}}" class="block border-b-[1px] border-[#0000001A] hover:bg-[#f7f7f7] px-3 py-1 text-[12px] text-gray-700">Logs</a>
                            {{-- <a href="#" class="block px-3 py-1 text-[12px] hover:bg-[#f7f7f7] text-gray-700">Delete</a> --}}
                        </div>
                    </div>
                </div>
            </td>
        </tr>
           @endforeach
        @endif                  
       
    </tbody>
</table>