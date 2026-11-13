<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;
use src\views\StoryView;

class StoryController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        $firstChapter = isset($request->get['chapitre']) ? (int) $request->get['chapitre'] : 0;
        if (isset($request->get['dialogue'])) {
            $firstDialogue = (int) $request->get['dialogue'];
        } elseif ($firstChapter > 0) {
            $firstDialogue = -1;
        } else {
            $firstDialogue = -2;
        }

        $vue = (new StoryView())->show();
        return $this->view->render('Story', $vue);
    }
}
