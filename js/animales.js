document.getElementById("Video").addEventListener("click", function() {
    if (this.paused) {
        this.play();
    } else {
        this.pause();
    }
});
