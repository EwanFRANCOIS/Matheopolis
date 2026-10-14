<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;
use modules\core\View;

class WorldController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        if ($request->session('email') !== null) {
            $activites = [
                "Voici l'introduction de Mathéopolis, une série de livres créées par l'association Maths pour tous avec
                le soutien des Instituts de recherche sur l'enseignement des mathématiques de Lyon et Aix Marseille...",
                "Laurence est une jeune fille ayant es difficultés a comprendre l'utilité des maths qu'elle apprend au
                lycées tout les jours. En effet, malgré la profession de son père mathématicien, elle ne l'a jamais 
                compris avant qu'il disparaisse sans laisser de trace...",
                "Toutefois, lors d'une session particulièrement intriguante sur 
                ses exercices de maths avec son grand père,
                elle apprendra qu'il y aurait quelque chose de plus grand que des symboles écrits sur son cahier..."
            ];

            $page = (int) $request->get('page', 1);
            $nombrePages = 3;

            $vue = (new \modules\views\WorldView())->show($activites, $page, $nombrePages);
            return $this->view->render('L\'univers', $vue);
        } else {
            return Response::redirect('index.php?action=login');
        }
    }
}
