import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

declare global {
    interface Window {
        Alpine: typeof Alpine;
    }
}
