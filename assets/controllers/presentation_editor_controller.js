import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "canvas",
        "colorInput",
        "imageInput",
        "posXInput",
        "posYInput",
        "scaleInput",
        "centerButton",
        "formatInput",
        "borderInput",
        "borderColorInput",
    ];
    static values = { bg: String, imageUrl: String, format: String };

    connect() {
        this.sizes = {
            "16:9": { width: 800, height: 450 },
            "4:3": { width: 800, height: 600 },
            "1:1": { width: 600, height: 600 },
        };

        this.image = null;
        this.draw();

        if (this.imageUrlValue) {
            this.loadImage(this.imageUrlValue, false);
        }

        this.previousScale = Number(this.scaleInputTarget.value);

        this.colorInputTarget.addEventListener("input", (event) => {
            this.bgValue = event.target.value;
            this.draw();
        });

        this.formatInputTarget.addEventListener("change", (event) => {
            const format = event.target.value;
            const size = this.sizes[format];
            this.canvasTarget.width = size.width;
            this.canvasTarget.height = size.height;
            this.center();
        });

        this.imageInputTarget.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            this.loadImage(URL.createObjectURL(file), true);
        });

        [this.posXInputTarget, this.posYInputTarget].forEach((input) => {
            input.addEventListener("input", () => this.draw());
        });

        this.borderInputTarget.addEventListener("input", () => {
            this.draw();
        });

        this.borderColorInputTarget.addEventListener("input", () => {
            this.draw();
        });

        this.scaleInputTarget.addEventListener("input", (event) => {
            const previousScale = this.previousScale;
            const scale = Number(event.target.value);

            const previousSize = this.getScaledSize(previousScale);
            const size = this.getScaledSize(scale);

            const currentX = Number(this.posXInputTarget.value);
            const currentY = Number(this.posYInputTarget.value);

            const newX = currentX + (previousSize.width - size.width) / 2;
            const newY = currentY + (previousSize.height - size.height) / 2;

            this.posXInputTarget.value = Math.round(newX);
            this.posYInputTarget.value = Math.round(newY);

            this.previousScale = scale;
            this.draw();
        });

        this.centerButtonTarget.addEventListener("click", () => {
            this.center();
        });
    }

    loadImage(url, shouldCenter = false) {
        const img = new Image();
        img.onload = () => {
            this.image = img;
            if (shouldCenter) {
                this.center();
            } else {
                this.draw();
            }
        };
        img.src = url;
    }

    getScaledSize(scale = Number(this.scaleInputTarget.value)) {
        const baseWidth = 600;
        const baseHeight = 350;

        return {
            width: baseWidth * scale,
            height: baseHeight * scale,
        };
    }

    center() {
        if (!this.image) {
            return;
        }

        const canvas = this.canvasTarget;
        const { width, height } = this.getScaledSize();

        const x = (canvas.width - width) / 2;
        const y = (canvas.height - height) / 2;

        this.posXInputTarget.value = Math.round(x);
        this.posYInputTarget.value = Math.round(y);

        this.draw();
    }

    draw() {
        const canvas = this.canvasTarget;
        const ctx = canvas.getContext("2d");
        ctx.fillStyle = this.bgValue || this.colorInputTarget.value;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        if (this.image) {
            const x = Number(this.posXInputTarget.value);
            const y = Number(this.posYInputTarget.value);
            const { width, height } = this.getScaledSize();

            ctx.drawImage(this.image, x, y, width, height);

            const borderWidth = Number(this.borderInputTarget.value);
            if (borderWidth > 0) {
                ctx.strokeStyle = this.borderColorInputTarget.value;
                ctx.lineWidth = borderWidth;
                ctx.strokeRect(x, y, width, height);
            }
        }
    }
}
