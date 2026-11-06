<?php

namespace src\views;

use src\views\components\Link;
use src\views\components\Paragraph;
use src\views\components\Form;
use src\views\components\Input;
use src\views\components\Button;

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

    public function __construct(
        private readonly ?string $token = null,
        private readonly ?string $messageError = null,
        private readonly bool $mailSent = false
    ) {
    }

    public function show(): string
    {
        $token = $this->token;
        $messageError = $this->messageError;
        $mailSent = $this->mailSent;

        $verifmail = new Paragraph([
            "Vérifiez votre boîte de réception."
        ]);

        $verifToken = new Paragraph([
            "Veuillez saisir votre nouveau mot de passe pour réinitialiser votre compte."
        ]);

        $saisieEmailText = new Paragraph([
            "Saisissez votre adresse email. 
            Si elle est associée à un compte, un lien de réinitialisation vous sera envoyé par mail."
        ]);

        $showMessageError = new Paragraph([
            htmlspecialchars($messageError ?? '')
        ], 'style="color: red;"');

        $showMessageSuccess = new Paragraph([
            "Un e-mail de réinitialisation vous a été envoyé si votre adresse existe dans notre base."
        ], 'style="color: green;"');

        $paragraphWithLink = new Paragraph([
            "Retour à la page de connexion ",
            new Link('/index.php?action=login', 'ici'),
            "."
        ]);

       $formNewPassword = new Form('/index.php?action=forgotpassword', [
            new Input(name: 'action_type', type: 'hidden', value: 'reset_password'),
            new Input(name: 'token', type: 'hidden', value: htmlspecialchars($token ?? '')),

            new Input(
                name: 'password',
                label: 'Nouveau mot de passe :',
                type: 'password',
                placeholder: 'Nouveau mot de passe',
                required: true
            ),
            new Button('Valider', 'submit', 'action', 'reset_password'),
        ]);

        $formRequestEmail = new Form('/index.php?action=forgotpassword', [
            new Input(name: 'action_type', type: 'hidden', value: 'request_reset'),

            new Input(
                name: 'email',
                label: 'Votre adresse email :',
                type: 'email',
                placeholder: 'Adresse email',
                required: true
            ),
            new Button('Envoyer le lien', 'submit', 'action', 'request_reset'),
        ]);
        ob_start();
        ?>


<header>
    <h1>Récupération du mot de passe</h1>
</header>

<div class="card">
    
    <?php if ($mailSent) : ?>
        <?= $verifmail ?>
    <?php elseif ($token) : ?>
        <?= $verifToken ?>
    <?php else : ?>
        <?= $saisieEmailText ?>
    <?php endif; ?>

</div>

<!-- Messages erreur / succes -->
        <?php if ($messageError) : ?>
            <?= $showMessageError ?>
        <?php endif; ?>

        <?php if ($mailSent) : ?>
    <!-- Message de conf et masquage du formulaire -->
    <?= $showMessageSuccess ?>
    <?= $paragraphWithLink ?>
        <?php else : ?>
    <!-- Formulaires s'affiche uniquement si mail n'est pas encore envoyé -->
    <div class="form-area">
            <?php if ($token) : ?>
            <!-- Formulaire : Nouveau mdp -->
            <?= $formNewPassword ?>
            <!--
            <form action="/index.php?action=forgotpassword" method="post">
                <input type="hidden" name="action_type" value="reset_password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">


                <label for="password">Nouveau mot de passe :</label>
                <input type="password" id="password" name="password" 
                placeholder="Nouveau mot de passe" class="input" required minlength="8">

                <button type="submit" class="btn">Valider</button>
            </form>
            -->
            <?php else : ?>
            <!-- Formulaire : Demande email -->
                <?= $formRequestEmail ?>
            <!--
            <form action="/index.php?action=forgotpassword" method="post">
                <input type="hidden" name="action_type" value="request_reset" >

                <label for="email">Votre adresse email :</label>
                <input type="email" id="email" name="email" placeholder="Adresse email" class="input" required >

                <button type="submit" class="btn">Envoyer le lien</button>
            </form>
            -->
            <?php endif; ?>
    </div>
        <?php endif; ?>

        <?php
       return ob_get_clean();
    }
}
?>
