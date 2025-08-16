import Alpine from "alpinejs";
import Aos from "aos";
import "../partials/loader";
import "../partials/toggle-theme";
import "../partials/menu";

window.Alpine = Alpine;
window.Alpine.start();

Aos.init({
    once: false,
    delay: 200,
});
