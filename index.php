<?php

require '_assets/includes/autoloader.php';

try {
    if (filter_input(INPUT_GET, 'action')) {
        if ($_GET['action'] === 'login') {
            (new \modules\controllers\LoginController())->execute();
        }

        else if ($_GET['action'] === 'signup') {
            (new \modules\controllers\SignupController())->execute();
        }

        else if ($_GET['action'] === 'account') {
            (new \modules\controllers\AccountController())->execute();
        }

        else if ($_GET['action'] === 'book') {
            (new \modules\controllers\BookController())->execute();
        }

        else if ($_GET['action'] === 'world') {
            (new \modules\controllers\WorldController())->execute();
        }

        else if ($_GET['action'] === 'activity') {
            (new \modules\controllers\ActivityController())->execute();
        }

        else if ($_GET['action'] === 'forgot_pwd') {
            (new \modules\controllers\ForgotPasswordController())->execute();
        }

        else if ($_GET['action'] === 'delete') {
            (new \modules\controllers\DeleteController())->execute();
        }

        else {
            throw new Exception('La page que vous recherchez n\'existe pas');
        }
    }
    else {
        (new \modules\controllers\HomepageController())->execute();
    }
} catch (Exception $e) {
    (new \modules\views\Error($e->getMessage()))->show();
}
?>