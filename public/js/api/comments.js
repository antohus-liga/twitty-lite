export async function getComments(postId) {
    const response = await fetch(`/api/posts/${postId}/comments`);
    return await response.json();
}

export async function createComment(postId, content) {
    const response = await fetch(`/api/posts/${postId}/comments`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({content})
    })
    return await response.json();
}

export async function likeComment(commentId) {
    const response = await fetch(`/api/comments/${commentId}/like`, {
        method: 'POST'
    });
    return await response.json();
}
