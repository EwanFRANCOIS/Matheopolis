<?php

// Inclusion de la classe de vue
require_once __DIR__ . '/modules/views/StoryView.php';

// Utilisation du namespace
use modules\views\StoryView;

// Instanciation et affichage du contenu retourné par la méthode show()
$view = new StoryView();
echo $view->show();