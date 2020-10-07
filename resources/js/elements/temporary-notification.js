var temporaryNotification = {

    slideUp: function () {
        //je stocke la notification en variable
        var notif = $('.notification');
        //s'il existe une notification
        if (notif.length) {
            //elle disparaitra via un effet slideUp après 3.5sec(3500 millisecondes)
            setTimeout(function () {
                notif.slideUp("slow");
            }, 3500);
        }
    }
};


temporaryNotification.slideUp();