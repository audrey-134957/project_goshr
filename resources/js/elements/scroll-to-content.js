var scrollToContent = {


    scrollSmooth: function () {
        var url = window.location.href;

        /*
        *je récupère la première valeur après le symbole # dans l'url pour la stocker en variable.
        *cette variable définira la carte du topic visée.
        */
        var endpoint = url.split("#")[1];

        var scrollSmoothBtn = $('.project-article-box__anchor-button');

        scrollSmoothBtn.on('click', function () {
            //je vais sroller jusqu'à la carte ciblée.
            $("html, body").stop().animate({ scrollTop: $($(this).attr('href')).offset().top - 150 }, 1000);

        });


        //if l'url contient bien la valeur de la variable endpoint
        if (url.indexOf(endpoint) > -1 && $('#' + endpoint).is('.topic-card') || $('#' + endpoint).is('.comment-card')) {
            //je vais cibler la cartdu topic et je lui ajoutera la class 'box--focus';
            $('#' + endpoint).addClass('box--focus');
            //je vais sroller jusqu'à la carte ciblée.
            $("html, body").stop().animate({ scrollTop: $('#' + endpoint).offset().top - 150 }, 2000);
        }
    }
};


scrollToContent.scrollSmooth();