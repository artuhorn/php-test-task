<?php

use App\Post;
use App\View;

require_once __DIR__ . '/../protected/autoload.php';

$posts = Post::find([], ['created_at DESC']);

$view = new View();
$view->posts = $posts;
$view->display('admin.posts.view.twig');
