import {apiFetch} from "./fetch.js";

export async function getUser(username) {
    const response = apiFetch(`/api/users/${username}`)
    return await response;
}