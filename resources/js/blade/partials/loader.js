import Alpine from 'alpinejs';

Alpine.data('loader', function () {
    return {
        isLoading: true,
        init() {
            window.addEventListener('load', () => {
                this.isLoading = false
                const timeout = setTimeout(() => {
                    document.body.style.overflowY = 'auto'
                    clearTimeout(timeout)
                }, 100)
            });
        },
    };
});
