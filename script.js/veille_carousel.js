const sites = [
  { title: 'ANSSI Cert-Fr', img: './assets/images/logo/certfr.png', link: 'https://www.cert.ssi.gouv.fr/' },
  { title: 'IT Connect', img: './assets/images/logo/itconnect.png', link: 'https://www.it-connect.fr/actualites/actu-securite/' },
  { title: 'Bleeping Computer', img: './assets/images/logo/bleeping_computer.png', link: 'https://www.bleepingcomputer.com' },
  { title: 'Linkedin', img: './assets/images/logo/linkedin.png', link: 'https://www.linkedin.com/company/anssi-fr/posts/?feedView=all' },
  { title: 'X - SaxX', img: './assets/images/logo/x.png', link: 'https://x.com/_saxx_/' },
  { title: 'Zataz', img: './assets/images/logo/zataz.png', link: 'https://www.zataz.com/' },
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
    inner.innerHTML = `<img src="${site.img}" alt="${site.title}" /><h5>${site.title}</h5>`;
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
