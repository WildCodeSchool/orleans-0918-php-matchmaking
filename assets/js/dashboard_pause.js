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