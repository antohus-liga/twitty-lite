import {loginView} from "./views/login.js";
import {registerView} from "./views/register.js";
import {feedView} from "./views/feed.js";
import {renderNavbar, setupNavbar} from "./components/navbar.js";

const routes = {
    '/': loginView,
    '/feed': feedView,
    '/login': loginView,
    '/register': registerView,
};

const publicRoutes = ['/login', '/register', '/'];

export function navigate(path) {
    history.pushState({}, '', path);
    render();
}

export function render() {
    const path = window.location.pathname;
    const view = routes[path];

    if (!publicRoutes.includes(path)) {
        document.getElementById('navbar').innerHTML = renderNavbar();
        setupNavbar();
    } else {
        document.getElementById('navbar').innerHTML = '';
    }

    if (view) {
        view();
    } else {
        document.getElementById('app').innerHTML = '<p>404 - Page not found</p>';
    }
}