<?php

namespace modules\views;

class Error
{
    public function __construct(private string $message)
    {
    }
    // On recup ici le $e-> message dans le routeur

    public function show(): string
    {
        ob_start();
        ?>
        <header><h1>Oops</h1></header>
        <p class="card"><?= htmlspecialchars($this->message) ?>
        <a href="index.php"> Retour à l'accueil</a>
        <?php
        return ob_get_clean();
    }
}

?>