document.getElementById("reptilesVideo").addEventListener("click", function() {
    if (this.paused) {
        this.play();
    } else {
        this.pause();
    }
});
