import Alpine from 'alpinejs';

Alpine.data('menu', function () {
    return {
        isOpen: false,
        init() {
            const original = this.$refs.element;
            const clone = original.cloneNode(true);
            this.$refs.mobileElement.appendChild(clone);
        },
        toggle() {
            this.$refs.menu.classList.toggle('active');
            this.$refs.overlay.classList.toggle('active');
            document.body.classList.toggle('overflow-y-hidden');
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                document.body.classList.add('overflow-y-hidden');
            } else {
                document.body.classList.remove('overflow-y-hidden');
            }
        },
    };
});

Alpine.data('subMenu', function () {
    return {
        isOpen: false,
        toggle() {
            this.isOpen = !this.isOpen;
            if (!this.isOpen) {
                this.close();
                return;
            }

            this.$refs.list.style.maxHeight = this.$refs.list.scrollHeight + 'px';
            this.$refs.wrap.classList.add('active');
        },
        close() {
            this.$refs.list.removeAttribute('style');
            this.$refs.wrap.classList.remove('active');
        },
        init() {
            this.close();
        },
    };
});
