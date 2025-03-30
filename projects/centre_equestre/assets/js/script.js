document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('lightbox');
    const lightboxContent = document.querySelector('.lightbox-content');
    const closeBtn = document.querySelector('.close');

    // Fonction pour ouvrir la lightbox
    function openLightbox(content) {
        lightbox.style.display = 'block';
        lightboxContent.innerHTML = content;
    }

    // Fonction pour fermer la lightbox
    function closeLightbox() {
        lightbox.style.display = 'none';
        lightboxContent.innerHTML = '';
    }

    // Ajouter les événements de clic sur les images et vidéos des membres
    document.querySelectorAll('.clickable-image, .responsive-video').forEach(item => {
        item.addEventListener('click', function() {
            let content;
            if (item.tagName === 'IMG') {
                content = `<img src="${item.src}" alt="${item.alt}">`;
            } else if (item.tagName === 'VIDEO') {
                content = `<video src="${item.querySelector('source').src}" controls autoplay></video>`;
            }
            openLightbox(content);
        });
    });

    // Ajouter l'événement de clic sur le bouton de fermeture
    closeBtn.addEventListener('click', closeLightbox);

    // Fermer la lightbox lorsque l'utilisateur clique en dehors du contenu
    window.addEventListener('click', function(event) {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });
});

// Autres scripts existants
document.querySelector('.hamburger-menu').addEventListener('click', function() {
    var sidenav = document.getElementById('sidenav');
    var mainContent = document.getElementById('main-content');
    if (sidenav.style.width === '250px') {
        sidenav.style.width = '0';
        mainContent.style.marginLeft = '0';
    } else {
        sidenav.style.width = '250px';
        mainContent.style.marginLeft = '250px';
    }
});

function closeNav() {
    document.getElementById('sidenav').style.width = '0';
    document.getElementById('main-content').style.marginLeft = '0';
}
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    const welcomeBtn = document.querySelector('.welcome-btn');

    welcomeBtn.addEventListener('click', function() {
        dropdown.classList.toggle('show');
    });

    window.addEventListener('click', function(event) {
        if (!event.target.matches('.welcome-btn')) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    });
});


function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".tablinks.active").click();
});

function confirmDeletion() {
    return confirm("Êtes-vous sûr de vouloir supprimer cette actualité ?");
}

document.addEventListener('DOMContentLoaded', function() {
    var currentPage = "<?php echo isset($currentPage) ? addslashes($currentPage) : ''; ?>";
    var message = "<?php echo addslashes($message); ?>";

    if (currentPage === 'contact' && message.trim() !== '') {
        alert(message);
    }
});
