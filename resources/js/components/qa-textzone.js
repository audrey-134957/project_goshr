var qaTextzone = {
    
    toggleEditTextzone: function () {
        var editTopicBtn = $('.topic-card__button--edit');

        editTopicBtn.on('click', function () {
            var topicFieldzone = $('.topic-card__textzone');
            $(this).next().next(topicFieldzone).toggleClass('is-hidden');
        });

    },

    toggleReplyTextzone: function (){
        var replyToTopicBtn = $('.topic-card__button--reply');

            replyToTopicBtn.on('click', function () {
                var replyTopicFieldzone = $('.topic-card__textzone--reply');
                $(this).next().next(replyTopicFieldzone).toggleClass('is-hidden');
            });
    }
};


qaTextzone.toggleEditTextzone();
qaTextzone.toggleReplyTextzone();