require('jquery-circle-progress');
document.addEventListener('DOMContentLoaded', function () {

    let route = "/dashboard/pause/";

    // Round Timer
    let globalTimerElt = document.querySelector(".globalTimer");
    let globalTextElt = document.querySelector(".globalTimer strong");
    let globalMinutes = globalTimerElt.dataset.globalMinutes;
    let globalSeconds= ("0" + globalTimerElt.dataset.globalSeconds).slice(-2);
    globalTextElt.textContent = globalMinutes + " : " + ("0" +globalSeconds).slice(-2);
    
    // Player Timer
    let playerTimerElt = document.querySelector(".roundTimer");
    let playerTextElt = document.querySelector(".timerText");
    let playerTime = playerTimerElt.dataset.roundSeconds;
    let playerDefMinutes =  Math.floor(playerTime / 60);
    let playerDefSeconds = ("0" + (playerTime - playerDefMinutes * 60)).slice(-2);
    let gradientStart = "#9999ff";
    let gradientEnd = "#5555ff";
    
    let eventId = globalTimerElt.dataset.eventId;
    let maxLaps = globalTimerElt.dataset.maxLaps;
    let currentLap = globalTimerElt.dataset.currentLap;

    setInterval(function () {
        globalSeconds= --globalSeconds<= -1 ? 59 : globalSeconds;
        if (globalSeconds== 0) {
            if (globalMinutes == 0) {
                if (currentLap < maxLaps) {
                    currentLap++;
                    let url = route + eventId + "/" + currentLap;
                    window.location.replace(url);
                }
            } else {
                globalMinutes--;
            }
        }

        globalTextElt.textContent = globalMinutes + " : " + ("0" +globalSeconds).slice(-2);
    }, 1000);

    function startCircleTimer() {
        let seconds = playerDefSeconds;
        let minutes = playerDefMinutes;
        playerTextElt.textContent = minutes + " : " + seconds;

        // initialisation du cercle
        $('.circle').circleProgress({
            value: 1,
            startAngle: 4.7,
            size: 0.3 * screen.width,
            reverse: true,
            animation: true,
            fill: {
                gradient: [gradientStart, gradientEnd]
            }
        });
        
        // decompte du timer à chaque seconde
        let circleInterv = setInterval(function () {
            seconds = --seconds <= -1 ? 59 : seconds;

            if (seconds == 0) {
                if (minutes == 0) {
                    clearInterval(circleInterv);
                    setTimeout( () => {
                        startCircleTimer();
                    }, 5000);
                } else {
                    minutes--;
                }
            }
    
            // actualisation du cercle
            $('.circle').circleProgress({
                value: (minutes * 60 + seconds) / playerTime,
                animation: false,
            });
    
            //affichage du temps sur la page
            playerTextElt.textContent = minutes + " : " + ("0" + seconds).slice(-2);
        }, 1000);
    }

    startCircleTimer();
}); 