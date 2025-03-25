document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('lightbox');
    const lightboxContent = document.querySelector('.lightbox-content');
    const closeBtn = document.querySelector('.close');
    const sidenav = document.getElementById('sidenav');
    const closeSidenavBtn = document.querySelector('.closebtn');

    console.log('Lightbox:', lightbox);
    console.log('Close Button:', closeBtn);

    if (lightbox && lightboxContent && closeBtn) {
        function openLightbox(content) {
            lightbox.style.display = 'block';
            lightboxContent.innerHTML = content;
        }

        function closeLightbox() {
            lightbox.style.display = 'none';
            lightboxContent.innerHTML = '';
        }

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

        closeBtn.addEventListener('click', closeLightbox);

        window.addEventListener('click', function(event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
    }

    // Dropdown functionality
    const dropdown = document.querySelector('.dropdown');
    const welcomeBtn = document.querySelector('.welcome-btn');

    if (welcomeBtn && dropdown) {
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
    }

    // Tab functionality
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

    if (document.querySelector(".tablinks.active")) {
        document.querySelector(".tablinks.active").click();
    }

    // Confirm deletion
    function confirmDeletion() {
        return confirm("Êtes-vous sûr de vouloir supprimer cette actualité ?");
    }

    // Display message on contact page
    var currentPage = "<?php echo isset($currentPage) ? addslashes($currentPage) : ''; ?>";
    var message = "<?php echo addslashes($message); ?>";

    if (currentPage === 'contact' && message.trim() !== '') {
        alert(message);
    }

    // Sidenav functionality
    function openNav() {
        sidenav.classList.add('open');
    }

    function closeNav() {
        sidenav.classList.remove('open');
    }

    if (closeSidenavBtn) {
        closeSidenavBtn.addEventListener('click', closeNav);
    }

    const openNavBtn = document.querySelector('button[onclick="openNav()"]');
    if (openNavBtn) {
        openNavBtn.addEventListener('click', openNav);
    }

    const dots = document.querySelectorAll('.dot');
    const skillCards = document.querySelectorAll('.skill-card');

    function showSkills(index) {
        skillCards.forEach(card => {
            if (parseInt(card.getAttribute('data-index')) === index) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            showSkills(index);
            dots.forEach(d => d.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Supprimer ou commenter la logique de changement automatique
    // let currentIndex = 0;
    // function autoChangeDots() {
    //     currentIndex = (currentIndex + 1) % dots.length;
    //     dots[currentIndex].click();
    // }
    // setInterval(autoChangeDots, 5000); // Changement automatique toutes les 5 secondes
});