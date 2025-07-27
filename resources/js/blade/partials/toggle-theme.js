import Alpine from 'alpinejs';

Alpine.data('toggleTheme', function () {
    return {
        isDark: false,
        init() {
            this.isDark = localStorage.getItem("theme") === "dark";
            console.log("Theme initialized:", this.isDark ? "Dark mode" : "Light mode");
        },
        toggle() {
            console.log("Toggling theme from", this.isDark ? "dark" : "light", "to", !this.isDark ? "dark" : "light");
            this.isDark = !this.isDark;
            localStorage.setItem("theme", this.isDark ? "dark" : "light");
            document.documentElement.classList.toggle('dark', this.isDark);
        },
    };
});
