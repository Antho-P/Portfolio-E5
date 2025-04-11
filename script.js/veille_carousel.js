
let scrollIndex = 0;
const carousel = document.getElementById("veille-carousel");

function scrollCarousel(direction) {
  const cards = carousel.querySelectorAll(".veille-card");
  const cardWidth = cards[0].offsetWidth + 32; // marge
  scrollIndex += direction;
  scrollIndex = Math.max(0, Math.min(scrollIndex, cards.length - 3));
  carousel.scrollTo({
    left: scrollIndex * cardWidth,
    behavior: "smooth"
  });
}
