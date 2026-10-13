<?php

namespace modules\views;

/**
 * Vue responsable de l'affichage de l'interface de récupération de mot de passe.
 */
class ForgotPasswordView
{
    /**
     * Affiche le formulaire ou le message de confirmation selon le contexte.
     *
     * @param string|null $token Token de réinitialisation transmis dans l'URL si présent
     * @param string|null $messageError Message d'erreur à afficher à l'utilisateur
     * @return void
     */

    public function show($token = null, $messageError = null): void
    {
        $mailSent = isset($_GET['mail']) && $_GET['mail'] === 'sent';
        ob_start();
        ?>


<header>
    <h1>Récupération du mot de passe</h1>
</header>

<div class="card">
    <p>
        <?php if ($mailSent) : ?>
        Vérifiez votre boîte de réception.
        <?php elseif ($token) : ?>
        Veuillez saisir votre nouveau mot de passe pour réinitialiser votre compte.
        <?php else : ?>
        Saisissez votre adresse email. 
        Si elle est associée à un compte, un lien de réinitialisation vous sera envoyé par mail.
        <?php endif; ?>
    </p>
</div>

<!-- Messages erreur / succes -->
        <?php if ($messageError) : ?>
    <p style="color: red;">
            <?= htmlspecialchars($messageError) ?>
    </p>
        <?php endif; ?>

        <?php if ($mailSent) : ?>
    <!-- Message de conf et masquage du formulaire -->
    <p style="color: green;">
        Un e-mail de réinitialisation vous a été envoyé si votre adresse existe dans notre base.
    </p>
    <p>
        <a href="index.php?action=login" class="btn">Retour à la connexion</a>
    </p>
        <?php else : ?>
    <!-- Formulaires s'affiche uniquement si mail n'est pas encore envoyé -->
    <div class="form-area">
            <?php if ($token) : ?>
            <!-- Formulaire : Nouveau mdp -->
            <form action="index.php?action=forgot_pwd" method="post">
                <input type="hidden" name="action_type" value="reset_password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">


                <label for="password">Nouveau mot de passe :</label>
                <input type="password" id="password" name="password" 
                placeholder="Nouveau mot de passe" class="input" required minlength="8">

                <button type="submit" class="btn">Valider</button>
            </form>
            <?php else : ?>
            <!-- Formulaire : Demande email -->
            <form action="index.php?action=forgot_pwd" method="post">
                <input type="hidden" name="action_type" value="request_reset" >

                <label for="email">Votre adresse email :</label>
                <input type="email" id="email" name="email" placeholder="Adresse email" class="input" required >

                <button type="submit" class="btn">Envoyer le lien</button>
            </form>
            <?php endif; ?>
    </div>
        <?php endif; ?>

        <?php
        (new \modules\views\Layout('Mot de passe oublié', ob_get_clean()))->show();
    }
}
?>
