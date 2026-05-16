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
        const content = document.getElementById('post-content').value;
        const result = await createPost(content);

        if (result.error) {
            document.getElementById('error-msg').textContent = result.error;
            return;
        }

        await feedView();
    });
}