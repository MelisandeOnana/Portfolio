document.addEventListener("DOMContentLoaded", function() {
    const skillCards = document.querySelectorAll(".skill-card");
    const dots = document.querySelectorAll(".dot");
    const totalCards = skillCards.length;
    let currentIndex = 0;

    function updateDots() {
        dots.forEach(dot => dot.classList.remove("active"));
        dots[Math.floor(currentIndex / 3)].classList.add("active");
    }

    function showNextCards() {
        for (let i = 0; i < 3; i++) {
            skillCards[(currentIndex + i) % totalCards].style.display = "none";
        }
        currentIndex = (currentIndex + 3) % totalCards;
        for (let i = 0; i < 3; i++) {
            skillCards[(currentIndex + i) % totalCards].style.display = "block";
        }
        updateDots();
    }

    function showPrevCards() {
        for (let i = 0; i < 3; i++) {
            skillCards[(currentIndex + i) % totalCards].style.display = "none";
        }
        currentIndex = (currentIndex - 3 + totalCards) % totalCards;
        for (let i = 0; i < 3; i++) {
            skillCards[(currentIndex + i) % totalCards].style.display = "block";
        }
        updateDots();
    }

    dots.forEach(dot => {
        dot.addEventListener("click", function() {
            const index = parseInt(this.getAttribute("data-index")) * 3;
            for (let i = 0; i < 3; i++) {
                skillCards[(currentIndex + i) % totalCards].style.display = "none";
            }
            currentIndex = index;
            for (let i = 0; i < 3; i++) {
                skillCards[(currentIndex + i) % totalCards].style.display = "block";
            }
            updateDots();
        });
    });

    for (let i = 0; i < 3; i++) {
        skillCards[i].style.display = "block";
    }
    updateDots();

    setInterval(showNextCards, 5000);
});

document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);

    fetch('send_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        showPopup(data);
        document.getElementById('contactForm').reset(); // Réinitialiser le formulaire
    })
    .catch(error => {
        showPopup('Erreur : ' + error);
    });
});

function showPopup(message) {
    var popup = document.getElementById('popup');
    var popupMessage = document.getElementById('popupMessage');
    var progressBar = document.getElementById('progressBar');

    popupMessage.innerText = message;
    popup.style.display = 'block';

    var width = 100;
    var interval = setInterval(function() {
        width--;
        progressBar.style.width = width + '%';
        if (width <= 0) {
            clearInterval(interval);
            popup.style.display = 'none';
        }
    }, 50);
}

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link'); // Correction ici

    function updateActiveLink() {
        let current = '';
        const scrollY = window.pageYOffset;
        const header = document.querySelector('.navbar');
        const headerHeight = header ? header.offsetHeight : 0;

        sections.forEach(section => {
            const sectionTop = section.offsetTop - headerHeight - 10;
            const sectionHeight = section.offsetHeight;
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (current && link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });

        // Ajoute ceci pour déplacer l'underline à chaque scroll
        moveUnderline();
    }

    window.addEventListener('scroll', updateActiveLink);
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

document.addEventListener("DOMContentLoaded", function() {
    const el = document.querySelector('.animated-text');
    if (!el) return;
    const text = el.textContent;
    el.textContent = '';
    let i = 0;
    function type() {
        if (i < text.length) {
            el.textContent += text.charAt(i);
            i++;
            setTimeout(type, 40);
        }
    }
    type();
});

document.addEventListener("DOMContentLoaded", function () {
    const nav = document.querySelector('.navbar-nav');
    if (!nav) return;
    let underline = document.createElement('div');
    underline.className = 'nav-underline-anim';
    nav.appendChild(underline);

    function moveUnderline() {
        const active = nav.querySelector('.nav-link.active');
        if (active) {
            const rect = active.getBoundingClientRect();
            const navRect = nav.getBoundingClientRect();
            underline.style.width = rect.width + "px";
            underline.style.left = (rect.left - navRect.left) + "px";
            underline.style.background = window.getComputedStyle(active, '::after').background || underline.style.background;
        } else {
            underline.style.width = 0;
        }
    }

    // Initial position
    moveUnderline();

    // Sur scroll (changement de section)
    window.addEventListener('scroll', moveUnderline);

    // Sur resize (responsive)
    window.addEventListener('resize', moveUnderline);

    // Sur clic menu
    nav.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            setTimeout(moveUnderline, 200);
        });
    });
});