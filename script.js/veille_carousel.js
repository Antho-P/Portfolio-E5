const sites = [
  { title: 'Site 1', src: './assets/', link: '#' },
  { title: 'Site 2', img: 'https://via.placeholder.com/150', link: '#' },
  { title: 'Site 3', img: 'https://via.placeholder.com/150', link: '#' },
  { title: 'Site 4', img: 'https://via.placeholder.com/150', link: '#' },
  { title: 'Site 5', img: 'https://via.placeholder.com/150', link: '#' },
];

let currentIndex = 0;

function renderCards() {
  const carousel = document.getElementById("veille-carousel");
  carousel.innerHTML = "";

  for (let i = -1; i <= 1; i++) {
    const index = (currentIndex + i + sites.length) % sites.length;
    const site = sites[index];

    const wrapper = document.createElement("div");
    wrapper.className = "card-wrapper";

    const card = document.createElement("div");
    card.className = "card";

    if (i === -1) card.classList.add("incl-left");
    if (i === 0) card.classList.add("card-main");
    if (i === 1) card.classList.add("incl-right");

    const inner = document.createElement("div");
    inner.className = "card-inner";
    inner.innerHTML = `<img src="${site.img}" alt="${site.title}" /><p>${site.title}</p>`;
    card.appendChild(inner);

    const link = document.createElement("div");
    link.className = "card-link";
    link.innerHTML = `<a href="${site.link}" target="_blank">Visiter le site</a>`;

    wrapper.appendChild(card);
    wrapper.appendChild(link);
    carousel.appendChild(wrapper);
  }
}

document.getElementById("prev-btn").addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + sites.length) % sites.length;
  renderCards();
});

document.getElementById("next-btn").addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % sites.length;
  renderCards();
});

renderCards();
