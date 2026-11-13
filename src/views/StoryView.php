<?php

namespace src\views;

use src\models\StoryModel;
use src\views\components\Typewriter;
use src\views\components\Link;

class StoryView
{
    public function show(int $n = 1): string
    {
        $storyFile = __DIR__ . '/../../public/data/story.json';
        $charactersFile = __DIR__ . '/../../public/data/charcters.json';

        $model = new StoryModel();

        $content = $model->readDialogue($n, $storyFile);
        
        if ($n === 11 || $n === 14 || $n === 39 || $n === 44) {
            $characterId = 2;
        } else {
            $characterId = 1;
        }
        $avatar = $model->getAvatar($characterId, $charactersFile);
        $name = $model->getPersonaName($characterId, $charactersFile);

        $hasSuite = $model->readDialogue($n + 1, $storyFile) !== '';

        ob_start();
        ?>
        <header>
            <h1>Bienvenue, plongez au coeur de Matheopolis !</h1>
        </header>

        <div class="card">
            <div class="dialog_wrapper">
                <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($name) ?>" class="dialog_profile">
                
                <div class="dialog_content">
                    <h3 class="dialog_name" style="margin-top: 0; margin-bottom: 8px;"><?= htmlspecialchars($name) ?></h3>
                    <?= (new Typewriter($content, 30))->render(); ?>
                </div>
            </div>
        </div>

        <div class="story-actions">
            <?php if ($hasSuite): ?>
                <?= (new Link("index.php?action=story&dialogue=" . ($n + 1), "Suivant >", "btn"))->render(); ?>
            <?php endif; ?>
            <?= (new Link("index.php?action=activity&step=game", "Activités |>", "btn"))->render(); ?>
        </div>

        <?php
        return ob_get_clean();
    }
}