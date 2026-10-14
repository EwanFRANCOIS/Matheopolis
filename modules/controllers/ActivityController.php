<?php

namespace modules\controllers;

use modules\core\Request;
use modules\core\Response;
use modules\core\View;

class ActivityController
{
    public function __construct(private readonly View $view)
    {
    }

    public function execute(Request $request): Response
    {
        if ($request->session('email') !== null) {
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
                $result = $op;
            $vue = (new \modules\views\ActivityView())->show($result);
            return $this->view->render('Activités', $vue);
        } else {
            return Response::redirect("index.php?action=login");
        }
    }
}
