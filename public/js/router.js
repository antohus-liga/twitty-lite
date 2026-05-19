import {loginView} from "./views/login.js";
import {registerView} from "./views/register.js";
import {feedView} from "./views/feed.js";
import {renderNavbar, setupNavbar} from "./components/navbar.js";
import {profileView} from "./views/profile.js";
import {postView} from "./views/post.js";
import {getCurrentUser} from "./state.js";
import {dmsListView} from "./views/dmsList.js";
import {dmsView} from "./views/dms.js";
import {leaderboardView} from "./views/leaderboard.js";

const routes = [
    { path: /^\/$/, view: loginView },
    { path: /^\/login$/, view: loginView },
    { path: /^\/register$/, view: registerView },
    { path: /^\/feed$/, view: feedView },
    { path: /^\/profile\/(\w+)$/, view: profileView },
    { path: /^\/post\/(\w+)$/, view: postView },
    { path: /^\/dms$/, view: dmsListView },
    { path: /^\/dms\/(\w+)$/, view: dmsView },
    { path: /^\/leaderboard$/, view: leaderboardView },
];

const publicRoutes = ['/login', '/register', '/'];

export async function render() {
    const path = window.location.pathname;
    const isPublic = publicRoutes.includes(path);

    if (!isPublic) {
        const user = getCurrentUser();
        document.getElementById('navbar').innerHTML = isPublic ? '' : renderNavbar(user.username);
        setupNavbar();
    }

    for (const route of routes) {
        const match = path.match(route.path);
        if (match) {
            const params = match.slice(1);
            await route.view(...params);
            return;
        }
    }

    document.getElementById('app').innerHTML = '<p>404 - Page not found</p>';
}