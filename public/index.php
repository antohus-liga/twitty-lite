<?php

session_start();

//if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'XMLHttpRequest') {
//    http_response_code(403);
//    echo json_encode(['error' => 'Forbidden']);
//    exit;
//}

require_once '../vendor/autoload.php';

use App\Controllers\CommentController;
use App\Controllers\LikeController;
use App\Controllers\MessageController;
use App\Controllers\UserController;
use App\Repositories\CommentRepository;
use App\Repositories\LikeRepository;
use App\Repositories\MessageRepository;
use App\Services\CommentService;
use App\Services\LikeService;
use App\Services\MessageService;
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
$messageRepository = new MessageRepository(Database::getInstance());

$userService = new UserService($userRepository);
$authService = new AuthService($userService);
$postService = new PostService($postRepository);
$likeService = new LikeService($likeRepository);
$commentService = new CommentService($commentRepository);
$messageService = new MessageService($messageRepository);

$authController = new AuthController($authService);
$postController = new PostController($postService);
$likeController = new LikeController($likeService);
$userController = new UserController($postService, $userService);
$commentController = new CommentController($commentService);
$messageController = new MessageController($messageService);

$router = new Router();
// Auth
$router->add('POST', '/api/register', [$authController, 'register']);
$router->add('POST', '/api/login', [$authController, 'login']);
$router->add('GET', '/api/me', [$authController, 'me'], true);
$router->add('POST', '/api/logout', [$authController, 'logout'], true);

// Posts
$router->add('GET', '/api/posts', [$postController, 'index']);
$router->add('POST', '/api/posts', [$postController, 'store'], true);
$router->add('GET', '/api/posts/{id}', [$postController, 'show'], true);
$router->add('PUT', '/api/posts/{id}', [$postController, 'update'], true);
$router->add('DELETE', '/api/posts/{id}', [$postController, 'remove'], true);

// Likes
$router->add('POST', '/api/posts/{id}/like', [$likeController, 'toggleOnPost'], true);
$router->add('POST', '/api/comments/{id}/like', [$likeController, 'toggleOnComment'], true);

// Comments
$router->add('GET', '/api/posts/{id}/comments', [$commentController, 'index']);
$router->add('POST', '/api/posts/{id}/comments', [$commentController, 'store'], true);
$router->add('PUT', '/api/comments/{id}', [$commentController, 'update'], true);
$router->add('DELETE', '/api/comments/{id}', [$commentController, 'remove'], true);

// User Profiles
$router->add('GET', '/api/users/{username}', [$userController, 'show']);
$router->add('PUT', '/api/users/profile', [$userController, 'updateProfile'], true);

// DMs
$router->add('GET', '/api/dms', [$messageController, 'conversations'], true);
$router->add('GET', '/api/dms/{id}', [$messageController, 'messages'], true);
$router->add('POST', '/api/dms/{id}', [$messageController, 'store'], true);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $path);