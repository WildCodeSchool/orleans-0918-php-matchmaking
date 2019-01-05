document.addEventListener('DOMContentLoaded', function () {

    let route = "/dashboard/run/";
    let pauseTimerElt = document.querySelector(".pauseTimer");
    let minutes = pauseTimerElt.dataset.pauseMinutes;
    let seconds = ("0" + pauseTimerElt.dataset.pauseSeconds).slice(-2);
    let eventId = pauseTimerElt.dataset.eventId;
    pauseTimerElt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
    let maxLaps = pauseTimerElt.dataset.maxLaps;
    let currentLap = pauseTimerElt.dataset.currentLap;

    setInterval(function () {
        seconds = --seconds <= -1 ? 59 : seconds;
        if (seconds == 0) {
            if (minutes == 0) {
                if (currentLap <= maxLaps) {
                    let url = route + eventId + "/" + currentLap;
                    window.location.replace(url);
                }
            } else {
                minutes--;
            }
        }

        pauseTimerElt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
    }, 1000);

});

// slide display-table
$(document).ready(function () {

    if ($('.pauseDashboard').length) {
        let displayTables = $('.slider .display-table');
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
});
