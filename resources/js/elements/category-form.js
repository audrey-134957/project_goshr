var categoryForm = {

    toggle: function () {
        var editBtn = $('.category-card__edit-button');

        var edit = $('.form--edit').closest(editBtn);

        editBtn.on('click', function () {
            // alert('ok');
            $(this).parent()
                .parent()
                .next(edit)
                .toggleClass('is-hidden');
        });
    }
};


categoryForm.toggle();