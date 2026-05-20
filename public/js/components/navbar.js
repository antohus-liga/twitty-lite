import {navigate} from "../navigate.js";
import {logout} from "../api/auth.js";
import {setCurrentUser} from "../state.js";

export function renderNavbar(username) {
    return `
        <nav>
            <a href="/feed" class="nav-logo">Twitty Lite</a>
            <div>
                <a href="/feed" class="nav-link">Feed</a>
                <a href="/dms" class="nav-link">Mensagens</a>
                <a href="/leaderboard" class="nav-link">Ranking</a>
                <a href="/profile/${username}" class="nav-link">${username}</a>
                <button id="logout-btn">Sair</button>
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
        setCurrentUser(null);
        navigate('/login');
    })
}