require('../css/dashboard.scss');
var $ = require('jquery');

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

document.addEventListener('DOMContentLoaded', function () {

    let globalTimerElt = document.querySelector(".globalTimer");
    let globalTextElt = document.querySelector(".globalTimer strong");
    let time = globalTimerElt.dataset.globalSeconds;
    let eventId = globalTimerElt.dataset.eventId;

    /* TODO : récupérer le nmbr de tour et le tour actuel */
    let laps = 3;
    let currentLap = 1;

    Timer(globalTextElt, time, 1000, () => {
        if (currentLap < laps) {
            currentLap++;
            let url = Routing.generate('dashboard_pause', { id: eventId, lap: currentLap });
            window.location.replace(url);
        } else {
            /* TODO : redirection vers page fin d'événement */
        }
    });


    function Timer(elt ,time, interval, callback) {
        let minutes = Math.floor(time / 60);
        let seconds = ("0" + (time - minutes * 60)).slice(-2);
        elt.textContent = minutes + " : " + seconds;

        setInterval(function () {
            if (seconds == 0) {
                if (minutes == 0) {
                    clearInterval(0);
                    callback();
                } else {
                    minutes--;
                }
            }
    
            seconds = --seconds <= -1 ? 59 : seconds;

            elt.textContent = minutes + " : " + ("0" +seconds).slice(-2);
        }, interval);
    }

}); 