import {loginView} from "./views/login.js";
import {registerView} from "./views/register.js";
import {feedView} from "./views/feed.js";
import {renderNavbar, setupNavbar} from "./components/navbar.js";
import {profileView} from "./views/profile.js";
import {me} from "./api/auth.js";
import {postView} from "./views/post.js";

const routes = [
    { path: /^\/$/, view: loginView },
    { path: /^\/login$/, view: loginView },
    { path: /^\/register$/, view: registerView },
    { path: /^\/feed$/, view: feedView },
    { path: /^\/profile\/(\w+)$/, view: profileView },
    { path: /^\/post\/(\w+)$/, view: postView },
];

const publicRoutes = ['/login', '/register', '/'];

export async function render() {
    const path = window.location.pathname;
    const isPublic = publicRoutes.includes(path);

    if (!isPublic) {
        const user = await me();
        document.getElementById('navbar').innerHTML = isPublic ? '' : renderNavbar(user.username);
        setupNavbar();
    }

    for (const route of routes) {
        const match = path.match(route.path);
        if (match) {
            const params = match.slice(1);
            route.view(...params);
            return;
        }
    }

    document.getElementById('app').innerHTML = '<p>404 - Page not found</p>';
}