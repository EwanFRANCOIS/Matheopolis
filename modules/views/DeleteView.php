<?php

namespace modules\views;

/**
 * Vue de la page de suppression.
 *
 * Génère le fodélecte rmulaire HTML de suprression et gère l'affichage
 * des messages d'erreur.
 */
class DeleteView
{
    /**
     * Affiche le formulaire de suppression du compte.
     *
     * @param string|null $error Message d'erreur éventuel à afficher à l'utilisateur.
     *
     * @return void
     */
    public function show(?string $error = null): string
    {
        ob_start();
        ?>

<header>
    <h1>Suppression</h1>
</header>

<div class="card">
    <div>
        <p>
        Voulez vous vraiment supprimer votre compte ?
        Veuillez écrire votre mot de passe pour confirmer sa suppression.
        </p>
    </div>
</div>

<div class="form-area">
    <form action="index.php?action=delete" method="post">
        <label for="password">Votre Mot de Passe :</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe" class="input" required >
        <div>
            <button type="submit" name="action" value="deleteUser" class="btn">Envoyer</button>
        </div>
    </form>

        <?php
        if ($error) {
            echo htmlspecialchars($error);
        }
        ?>
            
</div>

        <?php
        return ob_get_clean();
    }
}
?>