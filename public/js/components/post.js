import {likePost} from "../api/posts.js";
import {navigate} from "../navigate.js";

export function postTemplate(post) {
    return `
        <div class="post" data-id="${post.id}">
            <p class="post-timestamp">${dayjs(post.createdAt).fromNow()}</p>
            <a href="/profile/${post.username}" class="post-author">@${post.username}</a>
            <p class="post-content">${post.content}</p>
            <button class="like-btn" data-id="${post.id}">❤️ ${post.likeCount}</button>
            <button class="comment-btn" data-id="${post.id}">💬 ${post.commentCount}</button>
        </div>
    `;
}

export function setupPostListeners(containerId, onLike) {
    document.getElementById(containerId).addEventListener('click', async (e) => {
        if (e.target.classList.contains('post-author')) {
            e.preventDefault();
            const username = e.target.getAttribute('href').split('/').pop();
            navigate(`/profile/${username}`);
        }
        if (e.target.classList.contains('like-btn')) {
            const postId = e.target.dataset.id;
            await likePost(postId);
            onLike();
        }
        if (e.target.classList.contains('comment-btn')) {
            const postId = e.target.dataset.id;
            navigate(`/post/${postId}`);
        }
    });
}