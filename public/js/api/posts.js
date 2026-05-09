export async function getPosts() {
    const response = await fetch('/api/posts');
    return await response.json();
}

export async function createPost(content) {
    const response = await fetch('/api/posts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({content})
    })
    return response.json();
}

export async function likePost(postId) {
    const response = await fetch(`/api/posts/${postId}/like`, {
        method: 'POST'
    });
    return await response.json();
}

export async function getPost(postId) {
    const response = await fetch(`/api/posts/${postId}`);
    return await response.json();
}