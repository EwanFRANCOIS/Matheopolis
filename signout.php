<?php

    session_start();
    if(isset($_SESSION['email'])) {
        $_SESSION['email'] = null;
        $_SESSION['password'] = null;
        header("Location: index.php");
        exit();
    }
    else if ($_SESSION['email'] === null) {
        header("Location: index.php");
        exit();
    }

?>