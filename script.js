const menuToggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('#main-nav');

menuToggle?.addEventListener('click', () => {
  const isOpen = nav.classList.toggle('is-open');
  menuToggle.setAttribute('aria-expanded', String(isOpen));
});

// Remplacez ces exemples par de vrais avis clients, publiés avec leur accord.
const reviews = [
  { text: "Votre avis client pourra apparaître ici.", author: "Avis client à ajouter" }
];
let currentReview = 0;

function showReview() {
  document.querySelector('#review-text').textContent = reviews[currentReview].text;
  document.querySelector('#review-author').textContent = reviews[currentReview].author;
  document.querySelector('#review-count').textContent = `${currentReview + 1} / ${reviews.length}`;
}
function moveReview(direction) {
  currentReview = (currentReview + direction + reviews.length) % reviews.length;
  showReview();
}
document.querySelector('#previous-review').addEventListener('click', () => moveReview(-1));
document.querySelector('#next-review').addEventListener('click', () => moveReview(1));
document.querySelector('#year').textContent = new Date().getFullYear();
showReview();
