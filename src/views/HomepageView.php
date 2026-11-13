<?php

namespace src\views;

use src\views\components\Paragraph;
use src\views\components\Tile;

class HomepageView
{
    public function show(): string
    {
        $intro = new Paragraph([
            "Le site qui donne vie aux livres racontant l'histoire de l'héroïne Laurence Guerney,
            ce superbe projet de fiction créé par l'association \"Maths pour tous\"."
        ]);

        $info = new Paragraph([
            "Matheopolis est un projet pédagogique mais aussi une collection de romans illustrés 
            (portés par l'association 'Math Pour Tous' et les instituts de recherches 
            sur l'enseignement des mathématiques).
            Ses convictions résident donc dans une philosophie profonde de la vulgarisation 
            et de la transmission de la culture scientifique."
        ]);

        $site = new Paragraph([
            "Notre site vise à donner plus de visibilité sur Matheopolis mais surtout à aider 
            les créateurs à viser plus de collégiens ainsi que de lycéens pour les aider en mathématiques 
            mais aussi pour rendre celles-ci vivantes, concrètes et accessibles à travers une très grande aventure."
        ]);

        $tiles = [
            new Tile(
                href: 'index.php?action=book',
                symbol: '∑',
                title: 'Les Livres',
                description: "Découvrir les tomes et l'histoire de Laurence Guerney"
            ),
            new Tile(
                href: 'index.php?action=world',
                symbol: 'φ',
                title: "L'Univers",
                description: 'Plonger dans les mystères et les lieux de Matheopolis'
            ),
            new Tile(
                href: 'index.php?action=activity',
                symbol: '∞',
                title: 'Activités',
                description: 'Calculateur rapide, quiz et défis mathématiques'
            ),
        ];

        ob_start();
        ?>

<section class="split-home">
    <div class="split-left">
        <span class="small">BIENVENUE DANS LA VILLE DES MATHEMATIQUES</span>
        <h1>MATHEOPOLIS</h1>
        <p class="hero-subtitle">Redécouvrir les mathématiques à travers l'aventure de Laurence Guerney</p>
        
        <div class="split-intro">
            <?= $intro ?>
        </div>

        <div class="hero-actions">
            <a href="#explorer-grid" class="btn">Explorer l'univers</a>
            <a href="index.php?action=signup" class="btn btn-gold-fill">Rejoindre l'Ordre</a>
        </div>
    </div>

    <div class="split-right">
        <div class="card notebook watermark-card">
            <span class="small">LE PROJET</span>
            <h2>Objectif</h2>
            <?= $info ?>
        </div>

        <div class="card parchment">
            <span class="small red">LA MISSION</span>
            <h2>Notre site</h2>
            <?= $site ?>
        </div>
    </div>
</section>

<section id="explorer-grid">
    <div>
        <span class="small">ARCHIVES INTERACTIVES</span>
        <h2>Explorer le domaine</h2>
    </div>

    <div class="tile-grid">
        <?= implode('', $tiles) ?>
    </div>
</section>

        <?php
        return ob_get_clean();
    }
}