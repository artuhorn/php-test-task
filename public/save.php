<?php

use App\Post;
use App\Uploader;

require_once __DIR__ . '/../protected/autoload.php';

$id    = $_POST['id']    ?? '';
$title = $_POST['title'] ?? '';
$text  = $_POST['text']  ?? '';

if ($title == '' || $text == '') {
    header('Location: posts.php');
    exit;
}

$post = null;
if ($id !== '') {
    $post = Post::findById($id);
    if ($post === false) {
        // Обновляем пост, которого нет
        header('Location: posts.php');
    }
} else {
    $post = new Post();
}

if (!empty($_FILES['image']['tmp_name'])) {
    $image = (new Uploader($_FILES['image']))->upload();
    // Сохраняем имя файла с изображением в БД
    $post->image = $image;
}

$post->title = $title;
$post->text = $text;
$post->save();

header('Location: posts.php');
