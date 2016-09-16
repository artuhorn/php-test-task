<?php

use App\Post;

require_once __DIR__ . '/../protected/autoload.php';

$id = $_GET['id'] ?? '';

if ($id == '') {
    header('Location: posts.php');
    exit();
}

$post = Post::findById($id);
if ($post !== false) {
    $post->delete();
}

header('Location: posts.php');
