import {getCurrentUser} from "../state.js";

export function commentTemplate(comment) {
    const currentUser = getCurrentUser();
    const isOwner = currentUser && currentUser.username === comment.username;

    return `
        <div class="comment" data-id="${comment.id}">
            <p class="comment-timestamp">${dayjs(comment.createdAt).fromNow()}</p>
            <a href="/profile/${comment.username}" class="comment-author">@${comment.username}</a>
            <p class="comment-content">${comment.content}</p>
            <div class="actions">
                <button class="like-comment-btn ${comment.isLiked ? 'liked' : ''}" data-id="${comment.id}">❤️ ${comment.likeCount}</button>
                ${isOwner ? `
                    <button class="edit-comment-btn" data-id="${comment.id}">Edit</button>
                    <button class="delete-comment-btn" data-id="${comment.id}">Delete</button>
                ` : ''}
            </div>
        </div>
    `;
}