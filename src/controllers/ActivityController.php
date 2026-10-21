<?php

namespace src\controllers;

use src\core\Request;
use src\core\Response;
use src\core\View;
use src\views\StoryView;
use src\views\ActivityView;

class ActivityController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        if ($request->session('email') === null || $request->session('pseudonym')) {
            return Response::redirect("/index.php?action=login");
        }

        if (!$request->post('operation') && $request->get('step') !== 'game') {
            $storyVue = (new StoryView())->show();
            return $this->view->render('Introduction - Activités', $storyVue);
        }

        $op = 0;
        if ($request->post('operation')) {
            $action = $request->post('operation');
            $op1 = (float) $request->post('op1');
            $op2 = (float) $request->post('op2');

            if ($action == '+') {
                $op = $op1 + $op2;
            } elseif ($action == '-') {
                $op = $op1 - $op2;
            } elseif ($action == '*') {
                $op = $op1 * $op2;
            } elseif ($action == '/') {
                $op = $op1 / $op2;
            }
        } else {
            $result = "L'action n'a pas été enregistrée";
        }

        if ($request->post('operation')) {
            $result = $op;
        }

        $vue = (new ActivityView())->show($result);
        return $this->view->render('Activités', $vue);
    }
}
