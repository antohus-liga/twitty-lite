import {me} from "./api/auth.js";
import {render} from "./router.js";
import {navigate} from "./navigate.js";

dayjs.extend(dayjs_plugin_relativeTime);

async function init() {
    const user = await me();
    const publicRoutes = ['/', '/login', '/register'];
    const currentPath = window.location.pathname;

    if (user.error && !publicRoutes.includes(currentPath)) {
        navigate('/login');
    } else if (!user.error && publicRoutes.includes(currentPath)) {
        navigate('/feed');
    } else {
        render();
    }
}

window.addEventListener('popstate', render)

await init();