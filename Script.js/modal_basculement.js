function showCodePreview(imageSrc) {
    document.getElementById("modal-basculement").src = imageSrc;
    document.getElementById("code-modal").style.display = "flex";
}

function hideCodePreview() {
    document.getElementById("code-modal").style.display = "none";
}


document.addEventListener("DOMContentLoaded", function () {
   
document.getElementById("code-modal").style.display = "none";
});

// Effet de balancement
document.querySelectorAll('.code-button').forEach(button => {
    button.addEventListener('mousemove', (e) => {
        const rect = button.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const moveX = (x / rect.width - 1.5) * 10;
        const moveY = (y / rect.height - 1.5) * 10;

        button.style.transform = `rotateY(${moveX}deg) rotateX(${moveY}deg)`;
    });

    button.addEventListener('mouseleave', () => {
        button.style.transform = 'rotateY(0) rotateX(0)';
    });
});

