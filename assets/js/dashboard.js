require('../css/dashboard.scss');

var $ = require('jquery');
require('bootstrap');

/* let timerElt = document.getElementById("roundTimer").children[0];
timerElt.textContent = "oui"; */



document.addEventListener('DOMContentLoaded', function() {
    let timerElt = document.getElementById("roundTimer");
    var minutes = timerElt.dataset.roundMinutes;
    var seconds = timerElt.dataset.roundSeconds;
    timerElt.children[0].textContent = minutes + " : " + seconds;
});
