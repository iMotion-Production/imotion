import Alpine from 'alpinejs';

Alpine.data('videoModal', function () {
    return {
        popupOpen: false,
        openModal() {
            this.popupOpen = true;
            this.$nextTick(() => {
                this.$refs.videoPlayer.play();
            });
        },
        closeModal() {
            this.popupOpen = false;
            this.$refs.videoPlayer.pause();
            this.$refs.videoPlayer.currentTime = 0;
        }
    };
});
