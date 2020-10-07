var menu = {
    
    toggleVerticalMenu: function () {
        var menuBtn = $('.navbar__avatar');

        menuBtn.on('click', function () {
            var verticalMenu = $('.menu');
            verticalMenu.toggleClass('is-hidden');

        });
    }
};


menu.toggleVerticalMenu();