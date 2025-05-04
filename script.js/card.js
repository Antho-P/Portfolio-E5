document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("modal");
    const modalImg = document.getElementById("modal-img");
    const filterButtons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".cardportfolio-card");

    // Sélectionner tous les boutons "Détails"
    const detailButtons = document.querySelectorAll(".detail-btn");

    detailButtons.forEach(button => {
        button.addEventListener("click", function() {
            // Récupérer l'image du certificat associé au bouton
            const imgSrc = this.closest(".cardportfolio-container").querySelector("img").src;
            
            // Ouvrir la modale avec l'image
            modalImg.src = imgSrc;
            modal.style.display = "flex";
        });
    });

    // Fermer la modale en cliquant en dehors de l'image
    modal.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            // Retirer la classe "active" de tous les boutons
            filterButtons.forEach((btn) => btn.classList.remove("active"));
            // Ajouter "active" au bouton cliqué
            this.classList.add("active");

            // Récupérer la catégorie sélectionnée
            const category = this.getAttribute("data-category");

            // Filtrer les card
            cards.forEach((cardcontainer) => {
                if (category === "all" || cardcontainer.classList.contains(category)) {
                    cardcontainer.style.display = "block"; // Afficher
                } else {
                    cardcontainer.style.display = "none"; // Cacher
                }
            });
        });
    });
});
