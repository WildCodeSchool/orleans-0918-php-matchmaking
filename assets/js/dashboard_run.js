document.addEventListener('DOMContentLoaded', function () {

    let route = "/dashboard/pause/";
    let globalTimerElt = document.querySelector(".globalTimer");
    let globalTextElt = document.querySelector(".globalTimer strong");
    let minutes = globalTimerElt.dataset.globalMinutes;
    let seconds = ("0" + globalTimerElt.dataset.globalSeconds).slice(-2);
    let eventId = globalTimerElt.dataset.eventId;
    globalTextElt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
    let maxLaps = globalTimerElt.dataset.maxLaps;
    let currentLap = globalTimerElt.dataset.currentLap;

    setInterval(function () {
        seconds = --seconds <= -1 ? 59 : seconds;
        if (seconds == 0) {
            if (minutes == 0) {
                if (currentLap < maxLaps) {
                    currentLap++;
                    let url = route + eventId + "/" + currentLap;
                    window.location.replace(url);
                }
            } else {
                minutes--;
            }
        }

        globalTextElt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
    }, 1000);

}); 