require('../css/dashboard.scss');
var $ = require('jquery');
require('jquery-circle-progress');

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

require('bootstrap');

document.addEventListener('DOMContentLoaded', function () {
    let timerElt = document.querySelector(".roundTimer");
    let textElt = document.querySelector(".timerText");

    let time = timerElt.dataset.roundSeconds;
    let minutes = Math.floor(time / 60);
    let seconds = ("0" + (time - minutes * 60)).slice(-2);
    let eventId = timerElt.dataset.eventId;
    textElt.textContent = minutes + " : " + seconds;
    let gradientStart = "#9999ff";
    let gradientEnd = "#5555ff";

    // initialisation du cercle
    $('.circle').circleProgress({
        value: 1,
        startAngle: 4.7,
        size: 0.3 * screen.width,
        reverse: true,
        fill: {
            gradient: [gradientStart, gradientEnd]
        }
    });

    // decompte du timer à chaque seconde
    setInterval(function () {
        if (seconds == 0) {
            if (minutes == 0) {
                clearInterval(0);
                // redirection vers route symfony
                let url = Routing.generate('dashboard_pause', { id: eventId });
                window.location.replace(url);
            } else {
                minutes--;
            }
        }

        seconds = --seconds <= -1 ? 59 : seconds;
        // actualisation du cercle
        $('.circle').circleProgress({
            value: (minutes * 60 + seconds) / time,
            animation: false,
        });

        //affichage du temps sur la page
        textElt.textContent = minutes + " : " + ("0" + seconds).slice(-2);
    }, 1000);


});