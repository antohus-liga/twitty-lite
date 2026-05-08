export async function getUser(username) {
    const response = await fetch(`/api/users/${username}`);
    return await response.json();
}