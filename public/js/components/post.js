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