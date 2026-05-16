import {apiFetch} from "./fetch.js";

export async function getComments(postId) {
    const response = await apiFetch(`/api/posts/${postId}/comments`);
    return await response;
}

export async function createComment(postId, content) {
    const response = await apiFetch(`/api/posts/${postId}/comments`, {
        method: 'POST',
        body: JSON.stringify({content})
    })
    return await response;
}

export async function likeComment(commentId) {
    const response = await apiFetch(`/api/comments/${commentId}/like`, {
        method: 'POST'
    });
    return await response;
}
