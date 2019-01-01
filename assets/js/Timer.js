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
export default Timer;
