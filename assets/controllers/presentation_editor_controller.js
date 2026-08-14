import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "canvas",
        "color1Input",
        "imageInput",
        "posXInput",
        "posYInput",
        "scaleInput",
        "centerButton",
        "formatInput",
        "borderInput",
        "borderColorInput",
        "borderOpacityInput",
        "radiusInput",
        "shadowTypeInput",
        "shadowOpacityInput",
        "shadowAngleInput",
        "borderNumberInput",
        "posXNumberInput",
        "posYNumberInput",
        "scaleNumberInput",
        "borderOpacityNumberInput",
        "radiusNumberInput",
        "shadowOpacityNumberInput",
        "shadowAngleNumberInput",
        "exportButton",
        "color1HexLabel",
        "color2HexLabel",
        "imagePreview",
        "bgTypeInput",
        "bgSolidFields",
        "bgGradientFields",
        "bgImageFields",
        "color2Input",
        "gradientAngleInput",
        "backgroundImageInput",
        "gradientAngleNumberInput",
        "imageBackgroundPreview",
    ];
    static values = {
        bg: String,
        imageUrl: String,
        format: String,
        backgroundImageUrl: String,
    };

    connect() {
        this.sizes = {
            paysage: { width: 1000, height: 563 },
            standard: { width: 1000, height: 750 },
            carre: { width: 750, height: 750 },
        };

        this.updateBackgroundFields();

        this.image = null;
        this.backgroundImage = null;
        this.previewObjectUrl = null;

        const initialFormat =
            this.formatValue || this.formatInputTarget.value || "paysage";
        this.applyCanvasFormat(initialFormat);

        this.draw();

        if (this.backgroundImageUrlValue) {
            this.showBackgroundImagePreview(this.backgroundImageUrlValue);
            this.loadBackgroundImage(this.backgroundImageUrlValue);
        }

        if (this.imageUrlValue) {
            this.showImagePreview(this.imageUrlValue);
            this.loadOverlayImage(this.imageUrlValue, false);
        }

        this.previousScale = Number(this.scaleInputTarget.value);

        this.gradientAngleNumberInputTarget.value = Number(
            this.gradientAngleInputTarget.value,
        );

        this.posXNumberInputTarget.value = Number(this.posXInputTarget.value);
        this.posYNumberInputTarget.value = Number(this.posYInputTarget.value);
        this.scaleNumberInputTarget.value = Number(this.scaleInputTarget.value);

        this.borderNumberInputTarget.value = Number(
            this.borderInputTarget.value,
        );
        this.borderOpacityNumberInputTarget.value = Number(
            this.borderOpacityInputTarget.value,
        );
        this.radiusNumberInputTarget.value = Number(
            this.radiusInputTarget.value,
        );
        this.shadowOpacityNumberInputTarget.value = Number(
            this.shadowOpacityInputTarget.value,
        );
        this.shadowAngleNumberInputTarget.value = Number(
            this.shadowAngleInputTarget.value,
        );

        this.color1InputTarget.addEventListener("input", (event) => {
            this.color1HexLabelTarget.textContent = event.target.value;
            this.bgValue = event.target.value;
            this.draw();
        });

        this.bgTypeInputTargets.forEach((radio) => {
            radio.addEventListener("change", () => {
                this.updateBackgroundFields();
            });
        });

        this.color2InputTarget.addEventListener("input", () => {
            this.color2HexLabelTarget.textContent = event.target.value;
            this.draw();
        });

        this.gradientAngleInputTarget.addEventListener("input", () => {
            this.gradientAngleNumberInputTarget.value = Number(
                this.gradientAngleInputTarget.value,
            );
            this.draw();
        });

        this.gradientAngleNumberInputTarget.addEventListener("input", () => {
            this.gradientAngleInputTarget.value = Number(
                this.gradientAngleNumberInputTarget.value,
            );
            this.draw();
        });

        this.formatInputTargets.forEach((radio) => {
            radio.addEventListener("change", (event) => {
                this.applyCanvasFormat(event.target.value);
                this.center();
            });
        });

        this.imageInputTarget.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            this.showImagePreview(objectUrl, true);
            this.loadOverlayImage(objectUrl, true);
        });

        this.backgroundImageInputTarget.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            this.showBackgroundImagePreview(objectUrl, true);
            this.loadBackgroundImage(objectUrl);
        });

        this.posXInputTarget.addEventListener("input", (event) => {
            this.posXNumberInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.posXNumberInputTarget.addEventListener("input", (event) => {
            this.posXInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.posYInputTarget.addEventListener("input", (event) => {
            this.posYNumberInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.posYNumberInputTarget.addEventListener("input", (event) => {
            this.posYInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.borderInputTarget.addEventListener("input", (event) => {
            this.borderNumberInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.borderNumberInputTarget.addEventListener("input", (event) => {
            this.borderInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.borderColorInputTarget.addEventListener("input", () => {
            this.draw();
        });

        this.borderOpacityInputTarget.addEventListener("input", (event) => {
            this.borderOpacityNumberInputTarget.value = Number(
                event.target.value,
            );
            this.draw();
        });

        this.borderOpacityNumberInputTarget.addEventListener(
            "input",
            (event) => {
                this.borderOpacityInputTarget.value = Number(
                    event.target.value,
                );
                this.draw();
            },
        );

        this.radiusInputTarget.addEventListener("input", (event) => {
            this.radiusNumberInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.radiusNumberInputTarget.addEventListener("input", (event) => {
            this.radiusInputTarget.value = Number(event.target.value);
            this.draw();
        });

        this.shadowTypeInputTargets.forEach((radio) => {
            radio.addEventListener("change", () => {
                this.draw();
            });
        });

        this.shadowOpacityInputTarget.addEventListener("input", (event) => {
            this.shadowOpacityNumberInputTarget.value = Number(
                event.target.value,
            );
            this.draw();
        });

        this.shadowOpacityNumberInputTarget.addEventListener(
            "input",
            (event) => {
                this.shadowOpacityInputTarget.value = Number(
                    event.target.value,
                );
                this.draw();
            },
        );

        this.shadowAngleInputTarget.addEventListener("input", (event) => {
            this.shadowAngleNumberInputTarget.value = Number(
                event.target.value,
            );
            this.draw();
        });

        this.shadowAngleNumberInputTarget.addEventListener("input", (event) => {
            this.shadowAngleInputTarget.value = Number(event.target.value);
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
            this.syncPositionNumbers();

            this.scaleNumberInputTarget.value = Number(scale);

            this.previousScale = scale;
            this.draw();
        });

        this.scaleNumberInputTarget.addEventListener("input", (event) => {
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
            this.syncPositionNumbers();

            this.previousScale = scale;

            this.scaleInputTarget.value = Number(event.target.value);

            this.draw();
        });

        this.centerButtonTarget.addEventListener("click", () => {
            this.center();
        });

        this.exportButtonTarget.addEventListener("click", () => {
            this.export();
        });
    }

    applyCanvasFormat(format) {
        const size = this.sizes[format] || this.sizes["paysage"];
        this.canvasTarget.width = size.width;
        this.canvasTarget.height = size.height;
        this.draw();
    }

    syncPositionNumbers() {
        this.posXNumberInputTarget.value = this.posXInputTarget.value;
        this.posYNumberInputTarget.value = this.posYInputTarget.value;
    }

    loadOverlayImage(url, shouldCenter = false) {
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

    loadBackgroundImage(url) {
        const img = new Image();
        img.onload = () => {
            this.backgroundImage = img;
            this.draw();
        };
        img.src = url;
    }

    showImagePreview(url, isObjectUrl = false) {
        if (!this.hasImagePreviewTarget) {
            return;
        }

        if (this.previewObjectUrl) {
            URL.revokeObjectURL(this.previewObjectUrl);
            this.previewObjectUrl = null;
        }

        if (isObjectUrl) {
            this.previewObjectUrl = url;
        }

        this.imagePreviewTarget.src = url;
        this.imageInputTarget
            .closest(".image-field")
            ?.classList.add("is-filled");
    }

    showBackgroundImagePreview(url, isObjectUrl = false) {
        if (!this.hasBackgroundImagePreviewTarget) {
            return;
        }

        if (isObjectUrl) {
            this.previewBackgroundObjectUrl = url;
        }

        this.imageBackgroundPreviewTarget.src = url;
        this.backgroundImageInputTarget
            .closest(".image-field")
            ?.classList.add("is-filled");
    }

    getScaledSize(scale = Number(this.scaleInputTarget.value)) {
        const baseWidth = this.image.naturalWidth;
        const baseHeight = this.image.naturalHeight;

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
        this.syncPositionNumbers();

        this.draw();
    }

    export() {
        if (!this.image) {
            return alert("Aucune image chargée");
        }

        const canvas = this.canvasTarget;
        const link = document.createElement("a");
        link.download = "presentation.png";
        link.href = canvas.toDataURL("image/png");
        link.click();
    }

    draw() {
        const canvas = this.canvasTarget;
        const ctx = canvas.getContext("2d");
        const type =
            this.bgTypeInputTargets.find((radio) => radio.checked)?.value ??
            "solid";

        if (type === "solid") {
            ctx.fillStyle = this.bgValue || this.color1InputTarget.value;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        } else if (type === "degrade") {
            const color1 = this.color1InputTarget.value;
            const color2 = this.color2InputTarget.value;
            const angle = Number(this.gradientAngleInputTarget.value);

            const cx = canvas.width / 2;
            const cy = canvas.height / 2;

            const radians = (angle * Math.PI) / 180;
            const length = Math.max(canvas.width, canvas.height);

            const x1 = cx - Math.cos(radians) * length;
            const y1 = cy - Math.sin(radians) * length;
            const x2 = cx + Math.cos(radians) * length;
            const y2 = cy + Math.sin(radians) * length;

            const gradient = ctx.createLinearGradient(x1, y1, x2, y2);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        } else if (type === "image" && this.backgroundImage) {
            ctx.drawImage(
                this.backgroundImage,
                0,
                0,
                canvas.width,
                canvas.height,
            );
        }

        if (this.image) {
            const x = Number(this.posXInputTarget.value);
            const y = Number(this.posYInputTarget.value);
            const { width, height } = this.getScaledSize();
            const radius = Number(this.radiusInputTarget.value);
            this.drawShadow(ctx, x, y, width, height, radius);

            const borderWidth = Number(this.borderInputTarget.value);
            const borderOpacity = Number(this.borderOpacityInputTarget.value);
            const borderColor = this.hexToRGB(
                this.borderColorInputTarget.value,
                borderOpacity / 100,
            );
            if (borderWidth > 0) {
                ctx.strokeStyle = borderColor;
                ctx.lineWidth = borderWidth;
                ctx.beginPath();
                ctx.roundRect(x, y, width, height, radius);
                ctx.stroke();
            }

            ctx.save();
            ctx.beginPath();
            ctx.roundRect(x, y, width, height, radius);
            ctx.clip();
            ctx.drawImage(this.image, x, y, width, height);
            ctx.restore();
        }
    }

    drawShadow(ctx, x, y, width, height, radius) {
        const type =
            this.shadowTypeInputTargets.find((radio) => radio.checked)?.value ??
            "none";
        const opacity = Number(this.shadowOpacityInputTarget.value);
        const angle = Number(this.shadowAngleInputTarget.value);
        let blur = 0;
        let distance = 0;

        if (type === "none") {
            return;
        }

        if (type === "spread") {
            blur = 48;
            distance = 14;
        } else {
            blur = 4;
            distance = 8;
        }

        const shadowAngle = angle + 180;
        const rad = (shadowAngle * Math.PI) / 180;
        const offsetX = Math.cos(rad) * distance;
        const offsetY = Math.sin(rad) * distance;

        ctx.save();
        ctx.shadowColor = `rgba(0, 0, 0, ${opacity / 100})`;
        ctx.shadowBlur = blur;
        ctx.shadowOffsetX = offsetX;
        ctx.shadowOffsetY = offsetY;
        ctx.fillStyle = "#000";
        ctx.beginPath();
        ctx.roundRect(x, y, width, height, radius);
        ctx.fill();
        ctx.restore();
    }

    hexToRGB(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);

        if (alpha) {
            return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
        } else {
            return "rgb(" + r + ", " + g + ", " + b + ")";
        }
    }

    updateBackgroundFields() {
        const type =
            this.bgTypeInputTargets.find((radio) => radio.checked)?.value ??
            "solid";

        this.bgSolidFieldsTarget.hidden = type === "image";
        this.bgGradientFieldsTarget.hidden = type !== "degrade";
        this.bgImageFieldsTarget.hidden = type !== "image";
        this.draw();
    }
}
