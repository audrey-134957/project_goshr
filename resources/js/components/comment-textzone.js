var commentTextzone = {

    toggleEditTextzone: function () {

        var editCommentBtn = $('.comment-card__button--edit');

        editCommentBtn.on('click', function () {
            var editCommentFieldzone = $('.comment-card__textzone');
            $(this).next().next(editCommentFieldzone).toggleClass('is-hidden');
        });
    },

    toggleReplyTextzone: function () {
        var replyToCommentBtn = $('.comment-card__button--reply');

        replyToCommentBtn.on('click', function () {
            var replyCommentFieldzone = $('.comment-card__textzone--reply');
            $(this).next().next(replyCommentFieldzone).toggleClass('is-hidden');
        });

    },

};


commentTextzone.toggleEditTextzone();
commentTextzone.toggleReplyTextzone();