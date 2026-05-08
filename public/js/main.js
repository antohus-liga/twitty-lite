import {me} from "./api/auth.js";
import {render} from "./router.js";
import {navigate} from "./navigate.js";

dayjs.extend(dayjs_plugin_relativeTime);

async function init() {
    const user = await me();

    if(user.error) {
        navigate('/login');
    } else {
        await render();
    }
}

window.addEventListener('popstate', render)

await init();