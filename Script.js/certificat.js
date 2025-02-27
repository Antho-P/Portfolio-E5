document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("modal");
    const modalImg = document.getElementById("modal-img");
    const closeBtn = document.querySelector(".close");

    // Sélectionner tous les boutons "Détails"
    const detailButtons = document.querySelectorAll(".detail-btn");

    detailButtons.forEach(button => {
        button.addEventListener("click", function() {
            // Récupérer l'image du certificat associé au bouton
            const imgSrc = this.closest(".certificate-card").querySelector("img").src;
            
            // Ouvrir la modale avec l'image
            modalImg.src = imgSrc;
            modal.style.display = "flex";
        });
    });

    // Fermer la modale quand on clique sur la croix
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Fermer la modale en cliquant en dehors de l'image
    modal.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
