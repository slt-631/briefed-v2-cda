import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['canvas', 'colorInput', 'imageInput'];
    static values =
        { bg : String,
          imageUrl : String,
          format : String,
        };

    connect() {
        this.image = null;
        this.draw();

        if (this.imageUrlValue) {
            this.loadImage(this.imageUrlValue);
        }

        this.colorInputTarget.addEventListener('input', (event) => {
            this.bgValue = event.target.value;
            this.draw();
        });

        this.imageInputTarget.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;
            this.loadImage(URL.createObjectURL(file));
        });

    }

    loadImage(url) {
        const img = new Image();
        img.onload = () => {
            this.image = img;
            this.draw();
        };
        img.src = url;
    }
    draw() {
        const canvas = this.canvasTarget;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = this.bgValue || this.colorInputTarget.value;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        if (this.image) {
            ctx.drawImage(this.image, 100, 50, 600, 350);
        }
    }
}
