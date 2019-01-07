require('@fortawesome/fontawesome-free/js/all.js');
require('../css/dashboard.scss');


// slide display-table
$(document).ready(function () {

    if ($('.pauseDashboard').length) {
        let displayTables = $('.slider .display-table');
        if (displayTables.length > 1) {
            let circle = $('.slider .circle-display-screen');
            let indexDisplayTables = displayTables.length - 1;
            let i = 0;
            let currentDisplayTables = displayTables.eq(i);
            let currentCircle = circle.eq(i);

            displayTables.css('display', 'none');
            currentDisplayTables.css('display', 'flex');
            currentCircle.addClass("active-circle-display-screen");

            function slideDisplayTables()
            {
                setTimeout(function () {
                    if (i < indexDisplayTables) {
                        i++;
                    } else {
                        i = 0;
                    }

                    displayTables.hide();
                    currentDisplayTables = displayTables.eq(i);
                    currentDisplayTables.fadeIn(1000);
                    circle.removeClass("active-circle-display-screen");
                    currentCircle = circle.eq(i);
                    currentCircle.addClass("active-circle-display-screen");

                    slideDisplayTables();
                }, 6000);
            }

            slideDisplayTables();
        }
    }
});
