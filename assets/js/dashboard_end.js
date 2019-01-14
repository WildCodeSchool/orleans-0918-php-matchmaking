document.addEventListener('DOMContentLoaded', function () {
    let audio = $("#audioPlayer")[0];
    let soundPlayed = false;
    audio.load();

    if(!soundPlayed) {
        soundPlayed = true;
        audio.play(); 
    }
});
