<?php

namespace src\views;

use src\views\components\Paragraph;
use src\views\components\Link;

class WorldView
{
    public function show(
        array $activites,
        int $page,
        int $nombrePages
    ): string {
        $content = new Paragraph([
            $activites[$page - 1] ?? '',
        ]);

        $isActive = ($i === $page);
        $style = $isActive ? 'font-weight: bold;' : '';

        $prevPage = max(1, $page - 1);
        $nextPage = min($nombrePages, $page + 1);

        $worldPrevPage = new Link('/index.php?action=world&page=' . $prevPage, '←', 'page-link');
        $worldNextPage = new Link('/index.php?action=world&page=' . $nextPage, '→', 'page-link');

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
        <?= $worldPrevPage ?>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $nombrePages; $i++) :
            $isActive = ($i === $page);
            $style = $isActive ? 'font-weight: bold;' : null;
            $worldPageChange = new Link('/index.php?action=world&page=' . $i, (string)$i, 'page-link', $style);
        
            echo $worldPageChange;
        endfor; ?>

        <?php if ($page < $nombrePages) : ?>
        <?= $worldNextPage ?>
        <?php endif; ?>

</div>

        <?php
        return ob_get_clean();
    }
}
