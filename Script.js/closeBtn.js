document.addEventListener("DOMContentLoaded", function () {
    // Sélectionne toutes les modales et boutons de fermeture
    const modals = document.querySelectorAll(".modal, .cv-modal");
    const closeButtons = document.querySelectorAll(".close");

    // Ajoute l'événement de fermeture à chaque bouton close
    closeButtons.forEach(button => {
        button.addEventListener("click", function () {
            modals.forEach(modal => {
                modal.style.display = "none";
            });
        });
    });

    // Ajoute l'événement de fermeture en cliquant en dehors de la modale
    modals.forEach(modal => {
        modal.addEventListener("click", function (event) {
            if (!event.target.closest(".modal-content")) {
                modal.style.display = "none";
            }
        });
    });
});
