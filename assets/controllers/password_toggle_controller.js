import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["input", "icon"];

    toggle() {
        const show = this.inputTarget.type === "password";
        this.inputTarget.type = show ? "text" : "password";
        this.iconTarget.classList.toggle("fa-eye", !show);
        this.iconTarget.classList.toggle("fa-eye-slash", show);
    }
}
