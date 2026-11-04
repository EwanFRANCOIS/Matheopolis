<?php

namespace src\views;

use src\views\components\Paragraph;
use src\views\components\Link;

class WorldView
{
    public function __construct(
        private readonly array $activites,
        private readonly int $page,
        private readonly int $nombrePages
    ) {
    }

    public function show(): string {
        $activites = $this->activites;
        $page = $this->page;
        $nombrePages = $this->nombrePages;
        $content = new Paragraph([
            $activites[$page - 1],
        ]);


        $worldFirstPage = new Link('href="?action=world&page=<?= $page - 1 ?>"', '←');
        $worldPageChange = new Link('?action=world&page=<?= $i ?>" style="<?= $i === $page ?', 'style="<?= $i === $page ? "font-weight: bold;" : "" ?>');
        $worldLastPage = new Link('?action=world&page=<?= $page + 1 ?>', '→');

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
        <?= $worldFirstPage ?>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $nombrePages; $i++) : ?>
        <?= $worldPageChange ?>
        <?php endfor; ?>

        <?php if ($page < $nombrePages) : ?>
        <?= $worldLastPage ?>
        <?php endif; ?>

</div>

        <?php
        return ob_get_clean();
    }
}
