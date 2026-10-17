const textContainer = document.getElementById('story-text');
const btn = document.getElementById('next-story-btn');

const dialogues = [
    "Bienvenue",
    "Suite",
    "Fin"
];

let index = 0;
let timer = null;

function ecrire(texte, i = 0) {
    if (i === 0) textContainer.textContent = '';
    if (i < texte.length) {
        textContainer.textContent += texte[i];
        timer = setTimeout(() => ecrire(texte, i + 1), 35);
    }
}

function suivant() {
    clearTimeout(timer);
    ecrire(dialogues[index]);
    
    if (index === dialogues.length - 1) {
        btn.disabled = true;
    } else {
        index++;
    }
}

btn.addEventListener('click', suivant);
suivant();