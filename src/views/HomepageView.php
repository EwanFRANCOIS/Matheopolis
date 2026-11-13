<?php

namespace src\views;

use src\views\components\Paragraph;

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

        ob_start();
        ?>

<header>
    <span class="small">BIENVENUE SUR</span>
    <h1>Matheopolis</h1>
</header>
<div class="cards">
    <div class="card full">
        <?= $intro ?>
    </div>

    <div class="card notebook">
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

        <?php
        return ob_get_clean();
    }
}