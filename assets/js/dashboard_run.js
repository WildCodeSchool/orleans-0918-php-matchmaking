const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

document.addEventListener('DOMContentLoaded', function () {

    let globalTimerElt = document.querySelector(".globalTimer");
    let globalTextElt = document.querySelector(".globalTimer strong");
    let minutes = globalTimerElt.dataset.globalMinutes;
    let seconds = ("0" + globalTimerElt.dataset.globalSeconds).slice(-2);
    let eventId = globalTimerElt.dataset.eventId;
    globalTextElt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
    let maxLaps = globalTimerElt.dataset.maxLaps;
    let currentLap = globalTimerElt.dataset.currentLap;

    setInterval(function () {
        if (seconds == 0) {
            if (minutes == 0) {
                if (currentLap < maxLaps) {
                    currentLap++;
                    let url = Routing.generate('dashboard_pause', { id: eventId, currentLap: currentLap });
                    window.location.replace(url);
                }
            } else {
                minutes--;
            }
        }

        seconds = --seconds <= -1 ? 59 : seconds;
        globalTextElt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
    }, 1000);

}); 