import {me} from "./api/auth.js";
import {render} from "./router.js";
import {navigate} from "./navigate.js";
import {setCurrentUser} from "./state.js";

dayjs.extend(dayjs_plugin_relativeTime);

async function init() {
    const user = await me();
    if (!user.error) {
        setCurrentUser(user);
    }
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