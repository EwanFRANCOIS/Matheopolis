<?php

namespace modules\views;

/**
 * Vue de la page de connexion.
 *
 * Génère le formulaire HTML de connexion et gère l'affichage
 * des messages d'erreur.
 */
class LoginView
{
    /**
     * Affiche le formulaire de connexion.
     *
     * @param string|null $error Message d'erreur éventuel à afficher à l'utilisateur.
     *
     * @return void
     */
    public function show(?string $error = null): void
    {
        ob_start();
        ?>

<header>
    <h1>Connexion</h1>
</header>

<div class="card">
    <div>
        <p>
        Vous avez déjà vendu votre âme ? Connectez vous pour accéder à l'ensemble du site.
        </p>
    </div>
</div>

<div class="form-area">
    <form action="index.php?action=login" method="post">
        <label for="email">Votre adresse email :</label>
        <input type="email" id="email" name="email" placeholder="exemple@domaine.fr" class="input" required >
        <label for="password">Votre Mot de Passe :</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe" class="input" required >

        <div>
            <button type="submit" name="action" value="logUser" class="btn">Envoyer</button>
            <button type="reset" class="btn">Réinitialiser</button>
        </div>
    </form>

        <?php
        if ($error) {
            echo htmlspecialchars($error);
        }
        ?>
            
    <a href="index.php?action=forgot_pwd">Mot de passe oublié ?</a>
    <br><a href="index.php?action=signup">Se connecter</a>
</div>

        <?php
        (new \modules\views\Layout('Connection', ob_get_clean()))->show();
    }
}
?>