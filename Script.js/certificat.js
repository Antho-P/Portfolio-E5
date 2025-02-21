document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".filter-btn");
    const certificates = document.querySelectorAll(".certificate-card");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            const category = button.getAttribute("data-category");

            // Changer le style des boutons actifs
            buttons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            // Filtrage des certificats
            certificates.forEach(cert => {
                if (category === "all" || cert.classList.contains(category)) {
                    cert.style.display = "block";
                } else {
                    cert.style.display = "none";
                }
            });
        });
    });
});