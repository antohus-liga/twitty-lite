import {postTemplate} from "../components/post.js";
import {createPost, getPosts, likePost} from "../api/posts.js";

export async function feedView() {
    const posts = await getPosts();
    document.getElementById('app').innerHTML = `
        <div id="create-post">
            <textarea id="post-content" placeholder="What's on your mind?"></textarea>
            <button id="post-btn">Post</button>
        </div>
        <div id="feed">
            ${posts.map(postTemplate).join('')}
        </div>
    `;

    document.getElementById('feed').addEventListener('click', async(e) => {
        if (e.target.classList.contains('like-btn')) {
            const postId = e.target.dataset.id;
            await likePost(postId);
            await feedView();
        }
    });

    document.getElementById('post-btn').addEventListener('click', async () => {
        const content = document.getElementById('post-content').value;
        await createPost(content);
        await feedView();
    });
}