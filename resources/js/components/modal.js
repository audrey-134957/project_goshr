var modal = {

    open: function () {

        var modalBtn = $('.modal-button');

        $modal = $('.modal');

        modalBtn.on('click', function () {

            $(this).next($modal).addClass('is-active');

        });
    },

    close: function () {
        var closeModalBtn = $('.modal-close-button');
        closeModalBtn.on('click', function () {
            $modal.removeClass("is-active");
        });
    },

    checkIfMotiveIsSelected: function () {
        var submitBtn = $('.modal-submit-button');

        submitBtn.on('click', function (e) {

            console.log('clicked');

            var checkboxesExist = $('input[name="motives[]"]').length;
            var noCheckboxChecked = $('input[name="motives[]"]:checked').length === 0;

            if (checkboxesExist && noCheckboxChecked) {
                e.preventDefault();
                $('.modal-error').text('Vous devez choisir au moins 1 motif.');
            } else {
                $('.modal-error').text('');
            }
        })
    }
};


modal.open();
modal.close();
modal.checkIfMotiveIsSelected();