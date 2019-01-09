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
    let playerTime = Math.floor( ((playerTimerElt.dataset.roundSeconds)/ numberOfPlayers)-1);
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
    let bipAudio = $("#bipPlayer")[0];
    let speakAudio = $("#speakPlayer")[0];
    bipAudio.load();
    speakAudio.load();

    // interval Timer Round
    let globalInterv = setInterval(function () {
        let url = "";
        if (globalSeconds <= 0) globalMinutes--;
        globalSeconds = --globalSeconds <= -1 ? 59 : globalSeconds;
        globalTextElt.textContent = globalMinutes + " : " + ("0" + globalSeconds).slice(-2);

        if (globalSeconds == 0 && globalMinutes == 0) {
            clearInterval(globalInterv);

            if (currentLap < maxLaps) {
                currentLap++;
                url = route + eventId + "/" + currentLap;
            } else {
                url = "/dashboard/end/" + eventId;
            }
            window.location.replace(url);

        }

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
            if (seconds <= 0) minutes--;
            if (currentSpeaker < numberOfPlayers) {
                seconds = --seconds <= -1 ? 59 : seconds;
            } else if (currentSpeaker == numberOfPlayers) {
                seconds = globalSeconds;
                minutes = globalMinutes;
            }
            playerTextElt.textContent = minutes + " : " + ("0" + seconds).slice(-2);

            // actualisation du cercle
            $('.circle').circleProgress({
                value: (minutes * 60 + seconds) / playerTime,
                animation: false,
            });

            if (seconds == 0 && minutes == 0) {
                clearInterval(circleInterv);
                if (currentSpeaker < numberOfPlayers) {
                    speakAudio.play();
                    currentSpeaker++;
                }
                playerTextElt.classList.remove("alert-timer");
                setTimeout(() => {
                    seconds = 0;
                    playerTextElt.textContent = minutes + " : " + ("0" + seconds).slice(-2);

                    startCircleTimer();
                }, 1000);
            } else if (minutes == 0 && seconds <= 5) {
                bipAudio.play();
                playerTextElt.classList.add("alert-timer");
            }


        }, 1000);
    }

    startCircleTimer();
}); 