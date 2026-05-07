import {me} from "./api/auth.js";
import {navigate, render} from "./router.js";

async function init() {
    const user = await me();

    if(user.error) {
        navigate('/login');
    } else {
        navigate('/feed');
    }
}

window.addEventListener('popstate', render)

await init();