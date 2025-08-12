import Alpine from 'alpinejs';

Alpine.data('toggleTheme', function () {
    return {
        isDark: false,
        init() {
            this.isDark = localStorage.getItem("theme") === "dark";
        },
        toggle() {
            this.isDark = !this.isDark;
            localStorage.setItem("theme", this.isDark ? "dark" : "light");
            document.documentElement.classList.toggle('dark', this.isDark);
        },
    };
});
