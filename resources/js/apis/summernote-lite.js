require('summernote/dist/summernote-lite');


var summernote = {

    run: function () {

        $('.summernote').summernote({
            toolbar: [
                ['font', ['bold', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['picture']],
            ],
            disableDragAndDrop: true,
            disableResizeEditor: true,
            popover: {
                image: [],
                link: [],
                air: []
            },
            minHeight: 200,
            maximumImageFileSize: 6144,
            callbacks: {
                onImageUpload: function (files) {
                    var sizeKB = files[0]['size'] / 1024;
                    var tmp_pr = 0;
                    if (sizeKB > 6144) {
                        tmp_pr = 1;
                        alert("Votre image est trop lourde pour pouvoir être téléchargé.");
                        e.preventDefault();
                    }
                    if (!files.length) return;
                    var file = files[0];
                    // create FileReader
                    var reader = new FileReader();
                    reader.onloadend = function () {
                        // when loaded file, img's src set datauri
                        console.log("img", $("<img>"));
                        var img = $("<img>").attr({ src: reader.result, width: "60%" }).css({
                            'display': 'block',
                            'margin-left': 'auto',
                            'margin-right': 'auto',
                        }); // << Add here img attributes !
                        console.log("var img", img);
                        $('.summernote').summernote("insertNode", img[0]);
                    }

                    if (file) {
                        // convert fileObject to datauri
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    },

    hideStatusBar: function () {
        $('.note-statusbar').hide();

    },

    removeTooltip: function () {
        $('.note-tooltip').remove();
    },

    stickyToolbar: function () {
        if ((window.location.href.indexOf("je-poste-un-projet") > -1)
            ||
            (window.location.href.indexOf("modifier-mon-projet") > -1)
            ||
            (window.location.href.indexOf("modifier-mon-brouillon") > -1)) {
            $('.note-toolbar').css({
                'position': 'sticky',
                'top': '58px',
                'z-index': '3'
            });
        }
    },

    customImageModal: function () {

        if ((window.location.href.indexOf("je-poste-un-projet") > -1)
            ||
            (window.location.href.indexOf("modifier-mon-projet") > -1)
            ||
            (window.location.href.indexOf("modifier-mon-brouillon") > -1)) {



            // change the title of summernote image modal
            var modalTitle = $('.note-modal-title');
            modalTitle.text(function (i, oldText) {
                return oldText === 'Insert Image' ? 'Charger une image' : oldText;
            });

            //remove useless elements
            var modalContent = $('.note-modal-footer');
            var imageUrlField = $('.note-group-image-url');
            var imageModalBtn = $('.note-image-btn');
            modalContent.add(imageUrlField).add(imageModalBtn).remove();

        }
    },

    resizeImagesInsideSummernote: function (){
        var projectIllustration = $(".project-article__main img");
        
        projectIllustration.attr({ width: "100%"});

        if ((window.location.href.indexOf("je-poste-un-projet") > -1)
            ||
            (window.location.href.indexOf("modifier-mon-projet") > -1)
            ||
            (window.location.href.indexOf("modifier-mon-brouillon") > -1)) {

            var projectIllustration = $('.note-editable p img');
            projectIllustration.css({
                'width': '60%',
                'display': 'block',
                'margin-left': 'auto',
                'margin-right': 'auto'
            });
        }
    }
};


summernote.run();
summernote.hideStatusBar();
summernote.removeTooltip();
summernote.stickyToolbar();
summernote.customImageModal();
summernote.resizeImagesInsideSummernote();