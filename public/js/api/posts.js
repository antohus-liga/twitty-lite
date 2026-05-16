import {apiFetch} from "./fetch.js";

export async function getPosts() {
    const response = await apiFetch('/api/posts');
    return await response;
}

export async function createPost(content) {
    const response = await apiFetch('/api/posts', {
        method: 'POST',
        body: JSON.stringify({content})
    })
    return await response;
}

export async function likePost(postId) {
    const response = await apiFetch(`/api/posts/${postId}/like`, {
        method: 'POST'
    });
    return await response;
}

export async function getPost(postId) {
    const response = await apiFetch(`/api/posts/${postId}`);
    return await response;
}