<?php

session_start();

require_once '../vendor/autoload.php';

use App\Controllers\CommentController;
use App\Controllers\LikeController;
use App\Controllers\UserController;
use App\Repositories\CommentRepository;
use App\Repositories\LikeRepository;
use App\Services\CommentService;
use App\Services\LikeService;
use App\Services\UserService;
use Core\Database;
use Core\Router;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use App\Services\AuthService;
use App\Services\PostService;
use App\Controllers\AuthController;
use App\Controllers\PostController;

$userRepository = new UserRepository(Database::getInstance());
$postRepository = new PostRepository(Database::getInstance());
$likeRepository = new LikeRepository(Database::getInstance());
$commentRepository = new CommentRepository(Database::getInstance());

$userService = new UserService($userRepository);
$authService = new AuthService($userService);
$postService = new PostService($postRepository);
$likeService = new LikeService($likeRepository);
$commentService = new CommentService($commentRepository);

$authController = new AuthController($authService);
$postController = new PostController($postService);
$likeController = new LikeController($likeService);
$userController = new UserController($postService, $userService);
$commentController = new CommentController($commentService);

$router = new Router();
$router->add('POST', '/api/register', [$authController, 'register']);
$router->add('POST', '/api/login', [$authController, 'login']);
$router->add('GET', '/api/posts', [$postController, 'index']);
$router->add('POST', '/api/posts', [$postController, 'store'], true);
$router->add('POST', '/api/posts/{id}/like', [$likeController, 'toggle'], true);
$router->add('GET', '/api/me', [$authController, 'me'], true);
$router->add('POST', '/api/logout', [$authController, 'logout'], true);
$router->add('GET', '/api/users/{username}', [$userController, 'show']);
$router->add('GET', '/api/posts/{id}/comments', [$commentController, 'index']);
$router->add('POST', '/api/posts/{id}/comments', [$commentController, 'store'], true);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $path);