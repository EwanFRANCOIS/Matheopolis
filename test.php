<?php

require_once __DIR__ . '/_assets/includes/autoloader.php';

use modules\views\StoryView;
use modules\views\Layout;

$storyView = new StoryView();
$content = $storyView->show();

$layout = new Layout(
    title: 'Test Story',
    content: $content,
    userEmail: null
);

$response = $layout->show();
$response->send();