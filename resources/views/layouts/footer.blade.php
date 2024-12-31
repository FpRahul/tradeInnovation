<p class="text-center font-[Inter] text-[13px] font-[400] text-[#FFFFFF] leading-[19px] tracking-[-0.02em]">Copyright®2024 Company All right Reserved</p>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    jQuery(document).on('click', '.dropdown .button', function() {
        jQuery(this).parent().find('.dropdown_menu').toggleClass('hidden');
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

    // jQuery('').append()

    jQuery(document).on('click', '.has-submenu > a', function(){
        
    })
</script>