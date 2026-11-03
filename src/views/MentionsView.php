<?php

namespace src\views;

use src\views\components\Link;
use src\views\components\Paragraph;
use src\views\components\Strong;

class MentionsView
{
    public function show(): string
    {
        $cadreProjet = new Paragraph([
            'Ce site web est réalisé à des fins pédagogiques dans le cadre de notre formation en BUT Informatique. Il ne poursuit aucun but lucratif ou commercial.'
        ]);

        $editionIntro = new Paragraph([
            'Le site est développé et édité par :'
        ]);

        $directeurPublication = new Paragraph([
            new Strong('Directeur de publication : '),
            'Mickael Martin - Nevot'
        ]);

        $universite = new Paragraph([
            'IUT d\'Aix (Département Informatique)',
            '<br>',
            'Avenue Gaston Berger, 13625 Aix-en-Provence',
            '<br>',
            'Site web : ',
            new Link('https://www.univ-amu.fr', 'https://www.univ-amu.fr')
        ]);

        $hebergement = new Paragraph([
            'Le site est hébergé par :',
            '<br>',
            new Strong('AlwaysData'),
            '<br>',
            'Site web : ',
            new Link('https://www.alwaysdata.com/fr/', 'https://www.alwaysdata.com/fr/')
        ]);

        $donneesPersonnelles = new Paragraph([
            'Les informations recueillies via les formulaires (comptes utilisateurs, adresses email) sont strictement réservées au fonctionnement le plus basique du projet. Aucune donnée n\'est cédée à des tiers ni exploitée commercialement ou conservée à toutes fins d\'analyses.'
        ]);

        ob_start();
        ?>

<header>
    <h1>Mentions légales</h1>
</header>

<section class="mentions-legales">
    <article class="card">
        <h2>I - Cadre du projet</h2>
        <?= $cadreProjet ?>
    </article>

    <article class="card">
        <h2>II - Édition du site</h2>
        <?= $editionIntro ?>
        <ul>
            <li><?= new Strong('Ewan François') ?></li>
            <li><?= new Strong('Nicolas Moyenin') ?></li>
            <li><?= new Strong('Nina Matcha') ?></li>
            <li><?= new Strong('Aurèle Jambert') ?></li>
        </ul>
        <?= $directeurPublication ?>
    </article>

    <article class="card">
        <h2>III - Université</h2>
        <?= $universite ?>
    </article>

    <article class="card">
        <h2>IV - Hébergement</h2>
        <?= $hebergement ?>
    </article>

    <article class="card">
        <h2>V - Données personnelles et cookies</h2>
        <?= $donneesPersonnelles ?>
    </article>
</section>

        <?php
        return ob_get_clean();
    }
}