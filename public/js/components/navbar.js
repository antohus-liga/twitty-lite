import {navigate} from "../navigate.js";
import {logout} from "../api/auth.js";

export function renderNavbar(username) {
    return `
        <nav>
            <a href="/feed" class="nav-logo">Twitty Lite</a>
            <div>
                <a href="/feed" class="nav-link">Feed</a>
                <a href="/profile/${username}" class="nav-link">Profile</a>
                <a href="/dms" class="nav-link">DMs</a>
                <button id="logout-btn">Logout</button>
            </div>
        </nav>
    `;
}

export function setupNavbar() {
    document.querySelectorAll('.nav-link, .nav-logo').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            navigate(e.target.getAttribute('href'));
        });
    });

    document.getElementById('logout-btn').addEventListener('click', async () => {
        await logout();
        navigate('/login');
    })
}