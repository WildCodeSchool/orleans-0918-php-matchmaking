require('../css/dashboard.scss');
var $ = require('jquery');
import Timer from '../../assets/js/Timer.js';

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

}); 