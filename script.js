const popup = document.getElementById('popup');
const openBtn = document.getElementById('openPopup');
const closeBtn = document.querySelector('.close');

// Ouvrir le popup
openBtn.addEventListener('click', () => {
    popup.style.display = 'flex';
});

// Fermer le popup
closeBtn.addEventListener('click', () => {
    popup.style.display = 'none';
});

// Fermer si on clique en dehors du contenu
window.addEventListener('click', (e) => {
    if (e.target === popup) {
        popup.style.display = 'none';
    }
});
