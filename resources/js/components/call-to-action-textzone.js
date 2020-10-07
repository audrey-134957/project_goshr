var callToAction = {

    display: function () {
        var commentTextzone = $('.comments-section__textzone--call-to-action, .topics-section__textzone--call-to-action');
        commentTextzone.on('click', function (e) {
            var modal = $('.modal');
            e.preventDefault();
            modal.addClass("is-active");
        });

    },

    close: function () {
        var closeModalBtns = $('.modal-close');
        
        closeModalBtns.on('click', function () {
            var modal = $('.modal');
            modal.removeClass("is-active");
        });
    }

};


callToAction.display();
callToAction.close();
