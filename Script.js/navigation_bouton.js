document.addEventListener("DOMContentLoaded", () => {
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll("nav ul li a");

    function handleScroll() {
        let scrollPosition = window.scrollY + window.innerHeight / 2;
        let headerHeight = document.querySelector("header").offsetHeight; // Hauteur du header
    
        sections.forEach((section) => {
            let sectionTop = section.offsetTop - headerHeight - 10; // Ajustement
    
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + section.offsetHeight) {
                const activeSectionId = section.getAttribute("id");
    
                navLinks.forEach((link) => {
                    if (link.getAttribute("href").substring(1) === activeSectionId) {
                        link.classList.add("active");
                    } else {
                        link.classList.remove("active");
                    }
                });
            }
        });
    }
    
    // Lancement de la fonction lors du défilement
    window.addEventListener("scroll", handleScroll);
});
