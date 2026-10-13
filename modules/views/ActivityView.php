<?php

namespace modules\views;

class ActivityView
{
    public function show(string|float|null $result = null): string
    {
        ob_start();
        ?>

<header>
    <h1>Activités</h1>
</header>

<div class="card form-area">
    <h2>Calculatrice</h2>

    <form action="index.php?action=activity" method="post">
        <label for="op1">Opérande 1 :</label>
        <input type="text" name="op1" required />

        <label for="op2">Opérande 2 :</label>
        <input type="text" name="op2" required />

        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
            <button class="btn" type="submit" name="operation" value="+">+</button>
            <button class="btn" type="submit" name="operation" value="-">-</button>
            <button class="btn" type="submit" name="operation" value="*">×</button>
            <button class="btn" type="submit" name="operation" value="/">÷</button>
        </div>

        <button class="btn" type="reset">Réinitialiser</button>
    </form>
        <?php
        if ($result !== null) {
            ?>
        <div class="card parchment">
            <?= htmlspecialchars((string)$result); ?>
        </div>
            <?php
        }
        ?>
</div>

        <?php
        return ob_get_clean();
    }
}
?>