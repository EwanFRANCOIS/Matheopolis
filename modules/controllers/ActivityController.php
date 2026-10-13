<?php

namespace modules\controllers;

use modules\core\Response;

class ActivityController
{
    public function execute($request): Response
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
            return new Response($vue);
        } else {
            return Response::redirect("index.php?action=login");
        }
    }
}
