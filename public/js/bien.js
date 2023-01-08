let cards;
let containers;
const max = window.innerWidth / 2;

window.addEventListener("resize", () => {
    max = window.innerWidth / 2;
  });
  
document.addEventListener('DOMContentLoaded', () => {
    // Récupère tous les éléments .bien et .contenaire de la page
    cards = document.querySelectorAll('.bien');
    containers = document.querySelectorAll('.contenaire');

    // Boucle sur chaque élément .bien et .contenaire
    for (let i = 0; i < cards.length; i++) {
        const card = cards[i];
        const container = containers[i];

        // Récupérez les coordonnées de la carte
        let rect = card.getBoundingClientRect();
        // Ajoute les événements à chaque élément
        container.addEventListener('mousemove', (e) => {
            // Calculez l'axe x en utilisant les coordonnées de la carte
            let xAxis = (rect.width / 2 - (e.clientX - rect.left)) / 25;
            let yAxis = (rect.height / 2 - (e.clientY)) / 25;
            // Appliquez la rotation à l'élément card
            card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
            card.style.transformOrigin = '50% 50%';

        });

        container.addEventListener('mouseenter', (e) => {
            card.style.transformOrigin = '50% 50%';
            card.style.transition = 'none';
        });

        container.addEventListener('mouseleave', (e) => {
            card.style.transition = "all 0.5s ease";
            card.style.transformOrigin = '50% 50%';
            card.style.transform = `rotateY(0deg) rotateX(0deg)`;
        });
    }
});