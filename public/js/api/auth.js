export async function login(username, password) {
    const response = await fetch('/api/login', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({username, password})
    });
    return await response.json();
}

export async function register(username, password) {
    const response = await fetch('/api/register', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({username, password})
    });
    return await response.json();
}

export async function me() {
    const response = await fetch('/api/me')
    return await response.json();
}

export async function logout() {
    await fetch('/api/logout', {method: 'POST'});
}