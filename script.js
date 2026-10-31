const menuToggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('#main-nav');

menuToggle?.addEventListener('click', () => {
  const isOpen = nav.classList.toggle('is-open');
  menuToggle.setAttribute('aria-expanded', String(isOpen));
});

// Avis clients publiés avec leur accord.
const reviews = [
  {
    text: "Un grand merci à Florian d'Edenelia pour son professionnalisme lors de mes travaux d'entretien du jardin, tonte, haies et débroussaillage.\nUn travail de qualité, efficace et un vrai sens du service client. Je recommande vivement Edenelia.",
    author: "Patricia · Google · ★★★★★"
  },
  {
    text: "Monsieur très courageux même face à un travail de Titan ! Nous avons retrouvé un espace superbe grâce à lui, merci infiniment pour votre travail",
    author: "Vanessa M · Google · ★★★★★"
  },
  {
    text: "Merci beaucoup pour votre prestation !!! Ponctuel, soigneux et efficace...et en plus très sympathique !!\nJe recommande fortement cet artisan .",
    author: "Clo Thebest · Google · ★★★★★"
  },
  {
    text: "Florian est un très bon professionnel.\nIl est ponctuel, très attentif aux tâches à effectuer.\nIl a su s’adapter aux conditions de travail qu’il a rencontrés en arrivant sur l’espace à entretenir.\nIl sait faire preuve de très bonnes d’initiatives.\nJe le recommande vivement.\nJe le remercie pour le travail accompli.\n+++",
    author: "Nordine Bouzidi · Google · ★★★★★"
  },
  {
    text: "Nous sommes très satisfaits du travail effectué. L'intervenant est arrivé à l'heure ; il était très serviable, poli et a réalisé un excellent travail. Nous ferons sans aucun doute de nouveau appel à ses services. 👍🏻",
    author: "Robert Lovatt · Google · ★★★★★"
  },
  {
    text: "Florian est un très bon professionnel, une personne très agréable, ponctuel et soucieux du détail. Il est très investi dans son travail. Le résultat est impeccable et soigné. Je recommande vivement Florian et son entreprise ! Vous ne serez pas déçu. Personne de confiance. Merci pour le rendu , j'en suis satisfaite, mon jardin est magnifique",
    author: "Amandine Lecour · Google · ★★★★★"
  },
  {
    text: "Très satisfaite du travail réalisé! Ma haie a été parfaitement taillée.Jardinier sérieux et professionnel,je recommande sans hésiter ! merci pour votre travail.",
    author: "Corinne Jo · Google · ★★★★☆"
  },
  {
    text: "Mon garage était devenu un véritable musée du “au cas où”... Après des années à entasser des bêtises, j’ai enfin fait appel à Edénélia. En quelques heures, ils ont fait disparaître des années de bazar ! Résultat : un garage méconnaissable et un immense sentiment de légèreté. Merci à Florian pour tout.",
    author: "Jb Le Gall · Google · ★★★★★"
  },
  {
    text: "Rapide , efficace. Mon vieux jardin a le droit a une nouvelle vie après quelques années laissé à l'abandon\nJe remercie edélénia pour ce super travail",
    author: "Julien · Google · ★★★★★"
  },
  {
    text: "Merci beaucoup je suis très satisfaite de l’intervention d’Edénélia Tout sest très bien passé, dans une bonne ambiance. Les travaux ont été réalisés avec soin et le résultat est parfait. Je recommande vivement Edénélia, aussi bien pour l’entretien de votre jardin que pour des travaux de rénovation. Une entreprise sérieuse, efficace et professionnelle",
    author: "antanna9929 · Pages Jaunes · ★★★★★"
  },
  {
    text: "Très professionnel, efficace, disponible, serviable et réactif. Je recommande vivement ce jardinier qui a contribué à la remise en état d’un jardin en friche et à qui j’ai confié le suivi.",
    author: "Valérie Pécoud · Google · ★★★★★"
  },
  {
    text: "J’ai fait appel à Edenelia pour des travaux de jardinage et d’entretien, et je suis vraiment satisfait du résultat ! Professionnalisme, ponctualité et un super sens du détail. Le travail est propre et soigné, le tout avec une très bonne communication. Je recommande sans hésiter !",
    author: "Franck Loulouga · Google · ★★★★★"
  }
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
