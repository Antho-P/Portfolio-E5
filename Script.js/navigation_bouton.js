document.addEventListener("DOMContentLoaded", () => {
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll("nav ul li a");

    function handleScroll() {
        let scrollPosition = window.scrollY + window.innerHeight / 2;
        
        sections.forEach((section) => {
            if (scrollPosition >= section.offsetTop && scrollPosition < section.offsetTop + section.offsetHeight) {
                // On récupère l'ID de la section active
                const activeSectionId = section.getAttribute("id");

                // On boucle sur chaque lien de navigation
                navLinks.forEach((link) => {
                    if (link.getAttribute("href").substring(1) === activeSectionId) {
                        // Ajouter la classe active au lien correspondant à la section visible
                        link.classList.add("active");
                    } else {
                        // Supprimer la classe active des autres liens
                        link.classList.remove("active");
                    }
                });
            }
        });
    }
    // Lancement de la fonction lors du défilement
    window.addEventListener("scroll", handleScroll);
});
