import Alpine from "alpinejs";
import intersect from '@alpinejs/intersect';
import "../partials/loader";
import "../partials/toggle-theme";
import "../partials/menu";

Alpine.plugin(intersect);

window.Alpine = Alpine;
window.Alpine.start();
