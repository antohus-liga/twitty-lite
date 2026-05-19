import {apiFetch} from "./fetch.js";

export async function getLeaderboard() {
    return await apiFetch('/api/leaderboard');
}