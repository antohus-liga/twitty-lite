import {postTemplate} from "../components/post.js";
import {getPost, likePost} from "../api/posts.js";
import {navigate} from "../navigate.js";
import {createComment, getComments, likeComment} from "../api/comments.js";
import {commentTemplate} from "../components/comment.js";

export async function postView(postId) {
    const post = await getPost(postId);
    const comments = await getComments(postId);
    document.getElementById('app').innerHTML = `
        <div id="post-info">
            ${postTemplate(post)}
            <div id="create-comment">
                <textarea id="comment-content" placeholder="What do you think about this?"></textarea>
                <button id="submit-comment-btn">Comment</button>
            </div>
            <p id="error-msg" class="error"></p>
            <div id="comments">
                ${comments.map((comment) => commentTemplate(comment)).join('')}
            </div>
        </div>
    `;

    document.getElementById('post-info').addEventListener('click', async(e) => {
        if (e.target.classList.contains('post-author')) {
            e.preventDefault();
            const username = e.target.getAttribute('href').split('/').pop();
            navigate(`/profile/${username}`);
        }
        if (e.target.classList.contains('like-btn')) {
            const postId = e.target.dataset.id;
            await likePost(postId);
            await postView(postId);
        }
        if (e.target.classList.contains('like-comment-btn')) {
            const commentId = e.target.dataset.id;
            await likeComment(commentId);
            await postView(postId);
        }
    });

    document.getElementById('submit-comment-btn').addEventListener('click', async() => {
        const content = document.getElementById('comment-content').value;
        const btn = document.getElementById('submit-comment-btn');

        btn.disabled = true;

        const result = await createComment(postId, content);

        if (result.error) {
            document.getElementById('error-msg').textContent = result.error;
            btn.disabled = false;
            return;
        }

        await postView(postId);
    }) ;
}