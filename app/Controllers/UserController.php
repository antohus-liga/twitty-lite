<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\UserStats;
use App\Services\PostService;
use App\Services\UserService;

class UserController {
    private PostService $postService;
    private UserService $userService;

    public function __construct(PostService $postService, UserService $userService) {
        $this->postService = $postService;
        $this->userService = $userService;
    }

    public function show(string $username): void {
        $user = $this->userService->findByUsername($username);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            return;
        }



        $posts = $this->postService->getByUserId($user->id, $_SESSION['user_id']);

        http_response_code(200);
        echo json_encode([
            'user' => [
                'username' => htmlspecialchars($user->username),
                'createdAt' => $user->createdAt,
                'bio' => $user->bio ? htmlspecialchars($user->bio) : null,
                'dateOfBirth' => $user->dateOfBirth,
                'location' => $user->location,
                'website' => $user->website ? htmlspecialchars($user->website) : null,
                'occupation' => $user->occupation,
            ],
            'posts' => array_map(fn(Post $post) => [
                'id' => $post->id,
                'username' => htmlspecialchars($post->username),
                'content' => htmlspecialchars($post->content),
                'createdAt' => $post->createdAt,
                'likeCount' => $post->likeCount,
                'commentCount' => $post->commentCount,
                'isLiked' => $post->isLiked,
            ], $posts)
        ]);
    }

    public function updateProfile(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $this->userService->updateProfile(
                $_SESSION['user_id'],
                $data['bio'],
                $data['dateOfBirth'],
                $data['location'],
                $data['website'],
                $data['occupation']
            );
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Profile updated']);
    }

    public function leaderboard(): void {
        $leaderboard = $this->userService->getLeaderboard();
        http_response_code(200);
        echo json_encode(
            array_map(fn(UserStats $stats) => [
                'username' => htmlspecialchars($stats->username),
                'totalPostLikes' => $stats->totalPostLikes,
                'totalCommentLikes' => $stats->totalCommentLikes,
                'totalPosts' => $stats->totalPosts,
                'totalComments' => $stats->totalComments,
            ], $leaderboard)
        );
    }
}