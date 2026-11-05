<?php

namespace src\views;

use src\core\Response;
use src\views\components\Link;
use src\views\components\Button;
use src\views\components\Paragraph;

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

            $linkHomepage = new Link(href: '/index.php', text: 'Accueil');
            $linkBook = new Link(href: '/index.php?action=book', text: 'Les livres');
            $linkWorld = new Link(href: '/index.php?action=world', text: 'L\'univers');
            $linkActivity = new Link(href: '/index.php?action=activity', text: 'Activités');

            $buttonMenu = new Button(label: 'Menu ▾');

            $linkSignup = new Link(href: '/index.php?action=signup', text: 'Inscription');
            $linkLogin = new Link(href: '/index.php?action=login', text: 'Connexion');
            
            $linkProfile = new Link(href: '/index.php?action=profile', text: 'Profil');
            $linkLogout = new Link(href: '/index.php?action=logout', text: 'Déconnexion');

            $linkMention = new Link(href: '/index.php?action=mentions', text: 'Mentions légales');

            $paragraphFooter = new Paragraph(['Le présent du site à été réalisé par FRANCOIS Ewan, JAMBERT Aurele, KHADISSOVA Lezina,
            MATIC CHARBIT Nina et MOYENIN Nicolas.
            Hébergé par alwaysdata.
            Si vous souhaitez en savoir plus sur Matheopolis ou
            bien acheter les livres, rejoignez leur site web ci-dessous.']);

            $linkOfficialWebsite = new Link(href: 'https://www.matheopolis.org/', text: 'Site officiel Matheopolis');
            $linkSiteMap = new Link(href: '/sitemap.xml', text: 'Plan du site');

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
            <li><?=$linkHomepage?></li>
            <li><?=$linkBook?></li>
            <li><?=$linkWorld?></li>
            <li><?=$linkActivity?></li>
        </ul>
        <div class="dropdown">
            <?=$buttonMenu?>
            <div class="menu">
                <?php if ($this->userEmail === null) { ?>
                <?=$linkSignup?>
                <?=$linkLogin?>
                <?php } else { ?>
                <?=$linkProfile?>
                <?=$linkLogout?>
                <?php }?>
            </div>
        </div>
    </nav>

    <main>
            <?= $this->content; ?>
    </main>

    <footer>
        <h2><?=$linkMention?></h2>
        <?=$paragraphFooter?>
        <?=$linkOfficialWebsite?>
        <?=$linkSiteMap?>
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