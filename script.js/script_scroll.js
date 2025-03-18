// Fonction de défilement fluide avec animation
function smoothScroll(target, duration) {
    var targetElement = document.querySelector(target);
    if (!targetElement) return; // Vérifier si l'élément existe

    var headerHeight = document.querySelector("header").offsetHeight; 
    var targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight; // Décalage ajusté
    var startPosition = window.pageYOffset;
    var startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        var timeElapsed = currentTime - startTime;
        var run = ease(timeElapsed, startPosition, targetPosition - startPosition, duration);
        window.scrollTo(0, run);
        if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    function ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animation);
}

// Ajout des listeners sur les liens pour appliquer le scroll fluide
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        var target = this.getAttribute('href');
        smoothScroll(target, 1300); // Temps en ms
    });
});
