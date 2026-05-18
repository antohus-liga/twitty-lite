import {apiFetch} from "./fetch.js";

export async function getConversations() {
    return await apiFetch('/api/dms');
}

export async function getMessages(username) {
    return await apiFetch(`/api/dms/${username}`);
}

export async function sendMessage(username, content) {
    return await apiFetch(`/api/dms/${username}`, {
        method: 'POST',
        body: JSON.stringify({content})
    });
}