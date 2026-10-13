<?php

namespace modules\views;

class WorldView
{
    /**
     * Affiche la vue paginée des activités.
     *
     * @param array<string> $activites  Liste le texte.
     * @param int $page                  Numéro de la page courante.
     * @param int $nombrePages           Nombre de pages.
     */
    public function show(
        array $activites,
        int $page,
        int $nombrePages
    ): string {
    // PSR-12 : Accolade à la ligne pour la méthode
        ob_start();
        ?>

<header>
    <h1>L'univers</h1>
</header>

<div class="card">
    <h2>Contenus</h2>
    <div class="activite">
        <?= htmlspecialchars($activites[$page - 1]) ?>
    </div>

        <?php if ($page > 1) : ?>
        <a href="?action=world&page=<?= $page - 1 ?>">←</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $nombrePages; $i++) : ?>
        <a href="?action=world&page=<?= $i ?>" style="<?= $i === $page ? 'font-weight: bold;' : '' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>

        <?php if ($page < $nombrePages) : ?>
        <a href="?action=world&page=<?= $page + 1 ?>">→</a>
        <?php endif; ?>

</div>


        <?php
        return ob_get_clean();
    }
}
?>