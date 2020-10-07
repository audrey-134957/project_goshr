var fileReader = {

    run: function () {
        //je stocke la boxe de téléchargement de fichier en variable

        const fileImage = $('.box__input--file');
        //s'il y a du changement dans cette box
        fileImage.on('change', function () {

            
            //je stocke le label de l'image en variable
            const filePreview = $('.box__label--image');
            //je fais appel à une nouvel instance de FileReader
            const reader = new FileReader();
            //à son chargement
            reader.onload = function (e) {
                //l'image téléchargé est défini comme étant une image de fond dans une box
                filePreview.css('backgroundImage', 'url("' + e.target.result + '")');
                //je rajoute à la box qui contient l'image de fond une class
                filePreview.addClass("box__label--has-an-image");

                alert("Size: " + sizeKB + "KB\nWidth: " + img.width + "\nHeight: " + img.height);

            };
            //je compte le nb de fichiers
            var file = this.files[0];
            //je lis le fichier téléchargé
            reader.readAsDataURL(file);
        });
    },
};


fileReader.run();