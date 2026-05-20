import {postTemplate, setupPostListeners} from "../components/post.js";
import {createPost, getPosts} from "../api/posts.js";

export async function feedView() {
    const posts = await getPosts();
    document.getElementById('app').innerHTML = `
        <div id="create-post">
            <textarea id="post-content" placeholder="O que estás a pensar?"></textarea>
            <button id="post-btn">Publicar</button>
        </div>
        <p id="error-msg" class="error"></p>
        <div id="feed">
            ${posts.map(post => postTemplate(post)).join('')}
        </div>
    `;
    setupPostListeners('feed', () => feedView());

    document.getElementById('post-btn').addEventListener('click', async () => {
        const btn = document.getElementById('post-btn');
        const content = document.getElementById('post-content').value;

        btn.disabled = true;

        const result = await createPost(content);
        if (result.error) {
            document.getElementById('error-msg').textContent = result.error;
            btn.disabled = false;
            return;
        }

        await feedView();
    });
}