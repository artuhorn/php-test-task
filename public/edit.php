<?php

use App\Post;
use App\View;

require_once __DIR__ . '/../protected/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'] ?? '';

    $view = new View();

    // Если Id задан, значит показываем существующий пост для редактирования
    if ($id !== '') {
        $post = Post::findById($id);
        if ($post == false) {
            // Пост не найден
            header('Location: posts.php');
            exit();
        }
        $view->post = $post;
    }

    $view->display('admin.edit.view.twig');

}
