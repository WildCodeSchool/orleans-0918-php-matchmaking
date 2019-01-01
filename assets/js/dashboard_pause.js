require('../css/dashboard.scss');
var $ = require('jquery');
import Timer from './Timer.js';

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

document.addEventListener('DOMContentLoaded', function () {

    let pauseTimerElt = document.querySelector(".pauseTimer");
    let time = pauseTimerElt.dataset.pauseSeconds;
    console.log(time);
    let eventId = pauseTimerElt.dataset.eventId;

    /* TODO : récupérer le nmbr de tour et le tour actuel */
    let laps = 3;
    let currentLap = 1;

    Timer(pauseTimerElt, time, 1000, () => {
        if (currentLap < laps) {
            currentLap++;
            let url = Routing.generate('dashboard_run', { id: eventId, lap: currentLap });
            window.location.replace(url);
        } else {
            /* TODO : redirection vers page fin d'événement */
        }
    });

}); 