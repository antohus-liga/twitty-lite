export function postTemplate(post) {
    return `
        <div class="post" data-id="${post.id}">
            <p class="post-timestamp">${dayjs(post.createdAt).fromNow()}</p>
            <p class="post-author">@${post.username}</p>
            <p class="post-content">${post.content}</p>
            <button class="like-btn" data-id="${post.id}">❤️ ${post.likeCount}</button>
        </div>
    `;
}