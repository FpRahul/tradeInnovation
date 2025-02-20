@extends('layouts.default')
@section('content')

<div>
    <div class="flex items-center justify-between mb-[20px]">
        <h3 class="text-[20px] font-[400] leading-[24px] text-[#13103A] tracking-[0.02em]">Update Profile</h3>
    </div>

    <div class="shadow-[0px_0px_13px_5px_#0000000f] bg-white rounded-[20px] mb-[30px]">

        <form method="POST" action="{{ route('user.myprofile',['id'=>$userData->id])}}" enctype="multipart/form-data" class="py-[25px] px-[30px] space-y-[20px]">
            @csrf

            <div>
                <div>
                    <label for="profilePic" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Upload Profile Image<span class="text-[12px] italic font-[400] text-[#e70e0e]"> (only jpg,jpeg and png format supported & max:2 MB)</span></label>
                    <div class="relative flex flex-wrap gap-[10px]">

                        <img src="{{asset(isset($newUserDetails->uploadPhotograph) ? 'uploads/users/'.$userData->id.'/'.$newUserDetails->uploadPhotograph : 'assets/images/noimage.png')}}" width="70" height="70" class="getpreviewImage w-[150px] h-[150px] rounded-[10px] object-cover shadow-[0_0_5px_rgba(0,0,0,0.3)]" />
                        <div class="relative">
                            <label for="profilePic" class=" cursor-pointer w-[150px] h-[150px] rounded-[10px] flex items-center justify-center border border-dashed border-[#13103a4d] ">
                                <svg class="cursor-pointer" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.9395 8.95044H8.93945V13.9504C8.93945 14.5004 8.48945 14.9504 7.93945 14.9504C7.38945 14.9504 6.93945 14.5004 6.93945 13.9504V8.95044H1.93945C1.38945 8.95044 0.939453 8.50044 0.939453 7.95044C0.939453 7.40044 1.38945 6.95044 1.93945 6.95044H6.93945V1.95044C6.93945 1.40044 7.38945 0.950439 7.93945 0.950439C8.48945 0.950439 8.93945 1.40044 8.93945 1.95044V6.95044H13.9395C14.4895 6.95044 14.9395 7.40044 14.9395 7.95044C14.9395 8.50044 14.4895 8.95044 13.9395 8.95044Z" fill="#13103A" />
                                </svg>
                            </label>
                            <input type="file" name="profilePic" id="profilePic" class="previewImage w-0 opacity-0 absolute top-0 left-0">    
                            <div class="imageErrorMsg text-[12px] italic font-[400] text-[#e70e0e]"></div>
                       
                        </div>                        
                    </div>
                    @error('profilePic')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-[20px]">
                <div class="w-full md:w-1/3">
                    <label for="name" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Name</label>
                    <input type="text" name="name" id="name" value="{{ $userData->name}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Name">
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="w-full md:w-1/3">
                    <label for="email" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Email</label>
                    <input type="text" name="email" id="email" value="{{ $userData->email}}" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Email">
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-full md:w-1/3">
                    <label for="password" class="block text-[14px] font-[400] leading-[16px] text-[#000000] mb-[5px]">Password <span class="text-[12px] italic font-[400] text-[#5e5e5e]">(If required you can change the password)</span> </label>
                    <input type="text" name="password" id="password" value="" class="w-full h-[45px] border-[1px] border-[#0000001A] text-[14px] font-[400] leading-[16px] text-[#000000] tracking-[0.01em] px-[15px] py-[10px] rounded-[10px] !outline-none" placeholder="Enter Password">
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="">
                <button type="submit" class="text-[13px] font-[500] leading-[15px] text-[#ffffff] tracking-[0.01em] bg-[#13103A] rounded-[10px] py-[12px] px-[30px]">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('change','.previewImage' ,function () { 
        var file = this.files[0]; 
        var maxSize = 2 * 1024 * 1024;
        var allowedExtensions = ["jpg", "jpeg", "png"];  
        if (file) {
            var fileSize = file.size;
            var fileName = file.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();

            // Check file extension
            if (!allowedExtensions.includes(fileExtension)) {
                $(this).parent().find('.imageErrorMsg').text("Invalid file type. Allowed types: " + allowedExtensions.join(", "));
                $(this).val(""); // Clear file input
                return false;
            }

            // Check file size
            if (fileSize > maxSize) {
                $(this).parent().find('.imageErrorMsg').text("File size exceeds 2MB limit.");
                $(this).val(""); // Clear file input
                return false;
            }
            var input = event.target;
            var previewContainer = $(this).parent().parent().find('.getpreviewImage');   
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {                   
                    previewContainer.attr('src', e.target.result).show();                  
                    $('.imageErrorMsg').text('');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.hide();
                previewContainer.attr('src', '');
            }
        }
    }); 
</script>
@endsection