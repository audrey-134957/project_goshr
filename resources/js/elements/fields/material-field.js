var projectMaterialField = {
    
    addFieldDynamically: function () {
        var materialBtn = $('.materials-fields-box__button');

        materialBtn.on('click', function () {

            var materialFieldBox = $('.materials-fields-box__fields-box');
            var maxMaterialsInputs = 14;
            var numberOfInputs = materialFieldBox.find("input").length;
            var materialInput = numberOfInputs;

            if (materialInput < maxMaterialsInputs) {
                materialInput++;

                if (window.location.href.indexOf("/projets/je-poste-un-projet") > -1) {
                    var input = '<div class="materials-fields-box__material-box control">' +
                        '<span class="materials-fields-box__dot">&#x25CF;</span>' +
                        '<input class="is-rounded input" name="material[]" type="text" placeholder="" id="' + materialInput + '">' +
                        '</div>';

                } else {
                    var input = '<div class="materials-fields-box__material-box control">' +
                        '<span class="materials-fields-box__dot">&#x25CF;</span>' +
                        '<input class="is-rounded input" name="new_material[]" type="text" placeholder="" id="' + materialInput + '">' +
                        '</div>';
                }

                materialFieldBox.append(input);
            }

        });
    }
};


projectMaterialField.addFieldDynamically();