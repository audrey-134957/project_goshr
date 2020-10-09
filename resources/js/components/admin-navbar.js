var adminNavbar = {

    toggle: function(){
        var adminVerticalNavbarBtn = $('.admin-navbar__button--menu');

        var adminVerticalNavbar = $('.admin-menu');

        $(window).on('resize', function () {
            if ($(window).width() < 1024) {
                adminVerticalNavbar.addClass('is-hidden');

            } else if ($(window).width() >= 1024) {
                adminVerticalNavbar.removeClass('is-hidden');
            }
        });

        adminVerticalNavbarBtn.on('click', function () {
            adminVerticalNavbar.animate({ width: 'toggle' }, 500).removeClass('is-hidden');
        });
    }

}


adminNavbar.toggle();