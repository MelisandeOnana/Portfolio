document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.top-nav ul li a');

    function setActiveLink() {
        const sections = document.querySelectorAll('section');
        const scrollPosition = window.scrollY || window.pageYOffset;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    const targetLink = document.querySelector(`.top-nav ul li a[href="#${sectionId}"]`);
                    if (targetLink) {
                        link.classList.remove('active');
                        targetLink.classList.add('active');
                    }
                });
            }
        });
    }

    // Déclencher setActiveLink() lors du chargement de la page et du défilement
    setActiveLink();
    window.addEventListener('scroll', setActiveLink);
});

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggle-cards');
    const cards = document.querySelector('.cards');

    if (toggleButton && cards) {
        toggleButton.addEventListener('click', () => {
            if (cards.classList.contains('hidden')) {
                cards.classList.remove('hidden');
                toggleButton.textContent = 'Masquer mes apprentissages';
            } else {
                cards.classList.add('hidden');
                toggleButton.textContent = 'Voir mes apprentissages';
            }
        });
    }
});

// Configuration Tarteaucitron
tarteaucitron.init({
    "privacyUrl": "", /* URL de votre page de politique de confidentialité */
    "hashtag": "#tarteaucitron", /* Ouverture automatique */
    "cookieName": "tarteaucitron", /* Nom du cookie */
    "orientation": "bottom", /* Position de la bannière (top - bottom) */
    "showAlertSmall": false, /* Afficher une petite alerte en bas à droite */
    "cookieslist": false, /* Afficher la liste des cookies */
    "closePopup": false, /* Afficher un bouton pour fermer la bannière */
    "showIcon": true, /* Afficher l'icône */
    "iconPosition": "BottomRight", /* Position de l'icône (BottomRight - BottomLeft) */
    "adblocker": false, /* Afficher un message si un adblocker est détecté */
    "DenyAllCta": true, /* Afficher le bouton "Tout refuser" */
    "AcceptAllCta": true, /* Afficher le bouton "Tout accepter" */
    "highPrivacy": true, /* Désactiver le consentement implicite (en naviguant) */
    "handleBrowserDNTRequest": false, /* Respecter les paramètres Do Not Track du navigateur */
    "removeCredit": true, /* Supprimer le lien vers tarteaucitron.js */
    "moreInfoLink": true, /* Afficher le lien "En savoir plus" */
    "useExternalCss": false, /* Utiliser un fichier CSS externe */
    "readmoreLink": "", /* URL du lien "En savoir plus" */
    "mandatory": true /* Rendre le consentement obligatoire */
});

// Ajout de Google Analytics
tarteaucitron.user.analyticsUa = 'UA-XXXXXXX-X'; // Remplacez par votre identifiant UA Google Analytics
tarteaucitron.job = tarteaucitron.job || []; // Initialiser tarteaucitron.job s'il n'est pas défini
tarteaucitron.job.push('analytics');

document.addEventListener('DOMContentLoaded', function () {
    // Vérifier si le message de succès existe
    if (document.querySelector('.success-message')) {
        // Créer une popup pour le message de succès
        alert("Votre message a été envoyé avec succès. Merci de m'avoir contacté !");
    }
});

document.getElementById('contact-form').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    const prenom = document.getElementById('prenom').value;
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // Vérifier les caractères interdits dans le nom et le prénom
    const namePattern = /^[a-zA-ZÀ-ÿ\s'-]+$/;
    if (!namePattern.test(name) || !namePattern.test(prenom)) {
        alert("Veuillez entrer un nom et prénom valide sans chiffres ou symboles.");
        event.preventDefault();
        return false;
    }

    // Validation de l'email
    if (!emailPattern.test(email)) {
        alert("Veuillez entrer une adresse email valide.");
        event.preventDefault();
        return false;
    }
});


// Anni
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.project-card, .card, .article');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    });

    elements.forEach(element => {
        observer.observe(element);
    });
});

//
function checkWindowSize() {
    const sideNav = document.getElementById('side-nav');
    if (sideNav) {
        if (window.innerWidth > 500 && window.innerWidth < 1920) {
            sideNav.classList.add('hidden');
        } else {
            sideNav.classList.remove('hidden');
        }
    }
}

window.addEventListener('resize', checkWindowSize);
window.addEventListener('load', checkWindowSize);
