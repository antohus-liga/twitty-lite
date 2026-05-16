import {apiFetch} from "./fetch.js";

export async function login(username, password) {
    const response = await apiFetch('/api/login', {
        method: 'POST',
        body: JSON.stringify({username, password})
    });
    return await response;
}

export async function register(username, password) {
    const response = await apiFetch('/api/register', {
        method: 'POST',
        body: JSON.stringify({username, password})
    });
    return await response;
}

export async function me() {
    const response = await apiFetch('/api/me')
    return await response;
}

export async function logout() {
    await apiFetch('/api/logout', {method: 'POST'});
}