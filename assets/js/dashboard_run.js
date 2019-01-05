require('jquery-circle-progress');
document.addEventListener('DOMContentLoaded', function () {

    let route = "/dashboard/pause/";

    // Round Timer
    let globalTimerElt = document.querySelector(".globalTimer");
    let globalTextElt = document.querySelector(".globalTimer strong");
    let globalMinutes = globalTimerElt.dataset.globalMinutes;
    let globalSeconds = ("0" + globalTimerElt.dataset.globalSeconds).slice(-2);
    globalTextElt.textContent = globalMinutes + " : " + ("0" + globalSeconds).slice(-2);

    // Player Timer
    let playerTimerElt = document.querySelector(".roundTimer");
    let playerTextElt = document.querySelector(".timerText");
    let numberOfPlayers = playerTimerElt.dataset.numberOfPlayers;
    let playerTime = Math.floor(playerTimerElt.dataset.roundSeconds / numberOfPlayers);
    let playerDefMinutes = Math.floor(playerTime / 60);
    let playerDefSeconds = ("0" + (playerTime - playerDefMinutes * 60)).slice(-2);
    let gradientStart = "#9999ff";
    let gradientEnd = "#5555ff";

    //Speaker
    let speakerElt = document.querySelector(".speakerRound");
    let currentSpeaker = 1;

    let eventId = globalTimerElt.dataset.eventId;
    let maxLaps = globalTimerElt.dataset.maxLaps;
    let currentLap = globalTimerElt.dataset.currentLap;

    // interval Timer Round
    setInterval(function () {
        globalSeconds = --globalSeconds <= -1 ? 59 : globalSeconds;
        if (globalSeconds == 0) {
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

        globalTextElt.textContent = globalMinutes + " : " + ("0" + globalSeconds).slice(-2);
    }, 1000);

    function startCircleTimer() {
        let seconds = playerDefSeconds;
        let minutes = playerDefMinutes;
        playerTextElt.textContent = minutes + " : " + seconds;
        speakerElt.textContent = currentSpeaker + "/" + numberOfPlayers;

        // initialisation du cercle
        $('.circle').circleProgress({
            value: 1,
            startAngle: 4.7,
            size: 0.25 * screen.width,
            thickness: 20,
            reverse: true,
            animation: true,
            fill: {
                gradient: [gradientStart, gradientEnd]
            }
        });

        // interval Timer Player
        let circleInterv = setInterval(function () {

            seconds = --seconds <= -1 ? 59 : seconds;
            //affichage du temps sur la page
            
            if (seconds == 0) {
                if (minutes == 0) {
                    clearInterval(circleInterv);
                    currentSpeaker++;
                    startCircleTimer();
                } else {
                    minutes--;
                }
            }
            
            // actualisation du cercle
            $('.circle').circleProgress({
                value: (minutes * 60 + seconds) / playerTime,
                animation: false,
            });

            playerTextElt.textContent = minutes + " : " + ("0" + seconds).slice(-2);
        }, 1000);
    }

    startCircleTimer();
}); 