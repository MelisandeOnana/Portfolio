<section id="contact">
    <h2>Contactez-moi</h2>
    <form id="contactForm" method="post">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="objet">Objet:</label>
            <input type="text" id="objet" name="objet" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
    <div id="popup" class="popup" style="display:none;">
        <p id="popupMessage"></p>
        <div id="progressBar" class="progress-bar"></div>
    </div>
</section>