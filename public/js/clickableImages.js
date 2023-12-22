document.addEventListener('DOMContentLoaded', function() {
    var clickableImages = document.querySelectorAll('.clickable-image');

    clickableImages.forEach(function(img) {
        img.addEventListener('click', function() {
            document.getElementById('modal-image').src = this.src;
            document.getElementById('image-modal').style.display = 'flex';
        });
    });

    document.getElementById('image-modal').addEventListener('click', function() {
        this.style.display = 'none';
    });
});