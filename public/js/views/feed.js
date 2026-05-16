import {postTemplate, setupPostListeners} from "../components/post.js";
import {createPost, getPosts} from "../api/posts.js";

export async function feedView() {
    const posts = await getPosts();
    document.getElementById('app').innerHTML = `
        <div id="create-post">
            <textarea id="post-content" placeholder="What's on your mind?"></textarea>
            <button id="post-btn">Post</button>
        </div>
        <p id="error-msg" class="error"></p>
        <div id="feed">
            ${posts.map(postTemplate).join('')}
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