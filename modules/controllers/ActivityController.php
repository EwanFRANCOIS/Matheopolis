<?php

namespace modules\controllers;

class ActivityController
{
    public function execute(): void
    {
        session_start();
        if (isset($_SESSION['email'])) {
                $op = 0;

            if (isset($_POST['operation'])) {
                $action = $_POST['operation'];
                $op1 = (float) $_POST['op1'];
                $op2 = (float) $_POST['op2'];

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
            (new \modules\views\ActivityView())->show($result);
        } else {
            header("location:index.php?action=account");
            exit();
        }
    }
}
