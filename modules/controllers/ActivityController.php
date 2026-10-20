<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;
use modules\core\View;
use modules\views\StoryView;
use modules\views\ActivityView;

class ActivityController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        if ($request->session('email') === null) {
            return Response::redirect("index.php?action=login");
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

            if ($action === '+') {
                $op = $op1 + $op2;
            } elseif ($action === '-') {
                $op = $op1 - $op2;
            } elseif ($action === '*') {
                $op = $op1 * $op2;
            } elseif ($action === '/') {
                $op = $op2 != 0 ? ($op1 / $op2) : 0;
            }
        }

        $result = $op;
        $vue = (new ActivityView())->show($result);
        return $this->view->render('Activités', $vue);
    }
}