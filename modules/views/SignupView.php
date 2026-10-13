<?php

    namespace modules\views;

    /**
    * Vue responsable de l'affichage de la page d'inscription.
    */
class SignupView
{
    /**
    * Génère et affiche le code HTML du formulaire d'inscription.
    *
    * @param string|null $error Message d'erreur éventuel à afficher à l'utilisateur.
    * @return void
    */
    public function show(?string $error = null): string
    {
        ob_start();
        ?>

<header>
    <h1>S'inscrire</h1>
</header>

<div class=card>
    <p>
    Si vous voulez accedez à l'ensemble du site blah blah blah vendez votre âme et inscrivez vous :)
    </p>
</div>

<div class="form-area">
    <form method="post">
        <label for="email">Votre adresse email :</label>
        <input type="email" id="email" name="email" placeholder="exemple@domaine.fr" class="input" required >
        <label for="password">Votre Mot de Passe :</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe" class="input" required >
        <label for="verif">Vérification de votre Mot de Passe :</label>
        <input type="password" id="verif" name="verif" placeholder="Verification" class="input" required >

        <div>
            <button type="submit" name="action" value="inscription" class="btn">Envoyer</button>
            <button type="reset" class="btn">Réinitialiser</button>
        </div>
    </form>
        <?php
        if ($error) {
            echo htmlspecialchars($error);
        }
        ?>
    <a href="index.php?action=login">S'inscrire</a>
</div>

        <?php
        return ob_get_clean();
    }
}

?>