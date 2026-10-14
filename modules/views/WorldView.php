<?php

namespace modules\views;

use modules\views\components\Paragraph;

class WorldView
{
    public function show(
        array $activites,
        int $page,
        int $nombrePages
    ): string {
        $content = new Paragraph([
            $activites[$page - 1],
        ]);

        ob_start();
        ?>

<header>
    <h1>L'univers</h1>
</header>

<div class="card">
    <h2>Contenus</h2>
    <div class="activite">
        <?= $content ?>
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
