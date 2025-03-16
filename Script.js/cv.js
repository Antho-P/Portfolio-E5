document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("cv-modal");
    const closeBtn = document.querySelector(".close");
    const cvFrame = document.getElementById("cv-frame");
    const consulterBtn = document.querySelector(".open-modal"); // Cible seulement "Consulter CV"

    // Ouvrir la modale avec le CV
    consulterBtn.addEventListener("click", function(event) {
        event.preventDefault(); // Empêche l'ouverture en _blank
        cvFrame.src = this.href; // Charge le PDF dans l'iframe
        modal.style.display = "flex"; // Affiche la modale
    });

    // Fermer la modale
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
        cvFrame.src = ""; // Réinitialise l'iframe
    });

    // Fermer en cliquant en dehors
    modal.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
            cvFrame.src = "";
        }
    });
});
