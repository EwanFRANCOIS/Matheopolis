<?php

namespace src\views;

use src\core\Response;

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
            $_Image = 'https://ninamc.alwaysdata.net/_assets/Images/FubukiRef.jpg';
            $_description = 'Liens vers notre super site \'MATHEOPOLIS\'. 
            Suivez une aventure folle et accomplissez les énigmes tout au long de votre parcours.';
            $_titreRef = 'Matheopolis';

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
        <link rel="icon" href="/public/Images/favicon.png">
        <link rel="stylesheet" href="/public/style/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
    </head>
    <body>

    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="index.php?action=book">Les livres</a></li>
            <li><a href="index.php?action=world">L'univers</a></li>
            <li><a href="index.php?action=activity">Activités</a></li>
        </ul>
        <div class="dropdown">
            <button type="button">Menu ▾</button>
            <div class="menu">
                <?php if ($this->userEmail === null) { ?>
                <a href="index.php?action=signup">Inscription</a>
                <a href="index.php?action=login">Connexion</a>
                <?php } else { ?>
                <a href="index.php?action=profile">Profil</a>
                <a href="index.php?action=logout">Déconnexion</a>
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
        <a href="https://www.matheopolis.org/">Site officiel Matheopolis</a>
        <a href="sitemap.xml">Plan du site.</a>
    </footer>

    </body>
    </html>
        <?php
        $html = ob_get_clean();
        return new Response(content: $html, statusCode: $status);
    }
}

?>