<?php

namespace src\views;

use src\core\Response;
use src\views\components\Link;

class Layout
{
    public function __construct(
        private string $title,
        private string $content,
        private ?string $userEmail
    ) {
    }


    public function show(int $status = 200): Response
{
            $documentRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
            $_Image = '/Images/FubukiRef.jpg';
            $_description = 'Liens vers notre super site \'MATHEOPOLIS\'. 
            Suivez une aventure folle et accomplissez les énigmes tout au long de votre parcours.';
            $_titreRef = 'Matheopolis';

            $acceuil = new Link('/index.php', 'Acceuil');
            $livres = new Link('/index.php?action=book', 'Les livres');
            $world = new Link('/index.php?action=world', 'L\'univers');
            $activity = new Link('/index.php?action=activity', 'Activités');

            $signup = new Link('/index.php?action=signup', 'Inscription');
            $login = new Link('/index.php?action=login', 'Connexion');
            $profile = new Link('/index.php?action=profile', 'Profil');
            $logout = new Link('/index.php?action=logout', 'Déconnexion');

            $matheopolis = new Link('https://www.matheopolis.org/', 'Site officiel Matheopolis');
            $sitemap = new Link('/sitemap.xml', 'Plan du site.');

            ob_start();
            ?><!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta property="og:title" content="<?= htmlspecialchars($_titreRef) ?>">
        <meta name="description" content="<?= $_description ?>">
        <meta property="og:description" content="<?= htmlspecialchars($_description)?>">
        <meta property="og:image" content="<?= htmlspecialchars($_Image)?>">
        <meta property="og:image:type" content="image/jpeg">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Math, Matheopolis, Mathematique, Livre,
        Egypte, histoire, fubuki, bau bau, opération, calcul, Nevot, enigmes">
        <title><?= htmlspecialchars($this->title); ?></title>
        <link rel="icon" href="/Images/favicon.png">
        <link rel="stylesheet" href="/style/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
    </head>
    <body>

    <nav>
        <ul>
            <li><?= $acceuil ?></li>
            <li><?= $livres ?></li>
            <li><?= $world ?></li>
            <li><?= $activity ?></li>
        </ul>
        <div class="dropdown">
            <button type="button">Menu ▾</button>
            <div class="menu">
                <?php if ($this->userEmail === null) { ?>
                <?= $signup ?>
                <?= $login ?>
                <?php } else { ?>
                <?= $profile ?>
                <?= $logout ?>
                <?php }?>
            </div>
        </div>
    </nav>

    <main>
            <?= $this->content; ?>
    </main>

    <footer>
        <h2>Mention légales</h2>
        <p>
            Le présent du site à été réalisé par FRANCOIS Ewan, JAMBERT Aurele, KHADISSOVA Lezina,
            MATIC CHARBIT Nina et MOYENIN Nicolas.

            Hébergé par alwaysdata.

            <br>Si vous souhaitez en savoir plus sur Matheopolis ou
            bien acheter les livres, rejoignez leur site web ci-dessous.
        </p>
        <?= $matheopolis ?>
        <?= $sitemap ?>
    </footer>

    <script>
    const runTypewriters = async () => {
        const elements = document.querySelectorAll(".typewriter");

        function animateTypewriter(el) {
            return new Promise((resolve) => {
                const text = el.dataset.text || "";
                const speed = parseInt(el.dataset.speed, 10) || 50;
                let index = 0;

                function type() {
                    if (index < text.length) {
                        el.textContent += text.charAt(index);
                        index++;
                        setTimeout(type, speed);
                    } else {
                        resolve();
                    }
                }

                type();
            });
        }

        for (const el of elements) {
            await animateTypewriter(el);
        }
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", runTypewriters);
    } else {
        runTypewriters();
    }
    </script>
    </body>
    </html>
        <?php
        $html = ob_get_clean();
        return new Response(content: $html, statusCode: $status);
    }
}

?>