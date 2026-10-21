<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;

class WorldController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        if ($request->session('email') !== null || $request->session('username') !== null) {
            $activites = [
                "Voici l'introduction de Mathéopolis, une série de livres créée par l'association Maths pour tous avec
                le soutien des Instituts de recherche sur l'enseignement des mathématiques de Lyon et Aix-Marseille...",
                "Laurence est une jeune fille ayant des difficultés à comprendre l'utilité des maths qu'elle apprend au
                lycée tous les jours. En effet, malgré la profession de son père mathématicien, elle ne l'a jamais 
                comprise avant qu'il ne disparaisse sans laisser de trace...",
                "Toutefois, lors d'une session particulièrement intrigante sur 
                ses exercices de maths avec son grand-père,
                elle apprendra qu'il y a quelque chose de bien plus grand que des symboles écrits sur son cahier..."
            ];

            $page = (int) $request->get('page', 1);
            $nombrePages = 3;

            $vue = (new \src\views\WorldView())->show($activites, $page, $nombrePages);
            return $this->view->render('L\'univers', $vue);
        } else {
            return Response::redirect('/index.php?action=login');
        }
    }
}
