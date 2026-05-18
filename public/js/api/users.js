import {apiFetch} from "./fetch.js";

export async function getUser(username) {
    const response = await apiFetch(`/api/users/${username}`)
    return await response;
}

export async function updateProfile(bio, location, dateOfBirth, website, occupation) {
    const response = await apiFetch('/api/users/profile', {
        method: 'PUT',
        body: JSON.stringify({bio, location, dateOfBirth, website, occupation})
    })
    return await response;
}