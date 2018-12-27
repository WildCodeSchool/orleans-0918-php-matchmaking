require('../css/dashboard.scss');
var $ = require('jquery');

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

require('bootstrap');

document.addEventListener('DOMContentLoaded', function() {
    let timerElt = document.getElementById("roundTimer");

    // si on est sur la page "run"
    if(timerElt !== null) {
        let minutes = timerElt.dataset.roundMinutes;
        let seconds = ("0"+timerElt.dataset.roundSeconds).slice(-2);
        let eventId =  timerElt.dataset.eventId;
        timerElt.textContent = minutes + " : " + seconds;
        
        setInterval(function() {
            if(seconds == 0) {
                if(minutes == 0) {
                    clearInterval(0);
                    RedirectToRoute('dashboard_pause', {id: eventId});
                } else {
                    minutes--;
                }
            }
            seconds = --seconds <= -1 ? 59 : seconds;
            
            timerElt.textContent = minutes + " : " + ("0"+seconds).slice(-2);
        }, 1000);
    }

    function RedirectToRoute(path, param) {
        let url = Routing.generate(path, param);
        window.location.replace(url);
    }


});
