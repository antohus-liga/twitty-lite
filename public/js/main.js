import {createPost, getPosts, likePost} from "./api/posts.js";
import {renderFeed} from "./views/feed.js";
import {login, register} from "./api/auth.js";

async function load() {
    const posts = await getPosts();
    document.getElementById('app').innerHTML = renderFeed(posts);
}

document.addEventListener('click', async(e) => {
    if (e.target.classList.contains('like-btn')) {
        if (e.target.contains('liked')) {
            e.target.classList.remove('liked')
        } else {
            e.target.classList.add('liked')
        }
        const postId = e.target.dataset.id;
        await likePost(postId);
        load();
    }
});

document.getElementById('login-btn').addEventListener('click', async () => {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const result = await login(username, password);
    console.log(result);
    load();
});

document.getElementById('register-btn').addEventListener('click', async () => {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const result = await register(username, password);
    console.log(result);
});

document.getElementById('post-btn').addEventListener('click', async () => {
    const content = document.getElementById('post-content').value;
    await createPost(content);
    document.getElementById('post-content').value = '';
    load();
});

load();