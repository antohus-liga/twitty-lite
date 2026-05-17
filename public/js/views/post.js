import {postTemplate} from "../components/post.js";
import {editPost, getPost, likePost} from "../api/posts.js";
import {navigate} from "../navigate.js";
import {createComment, deleteComment, editComment, getComments, likeComment} from "../api/comments.js";
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
        if (e.target.classList.contains('delete-comment-btn')) {
            const commentId = e.target.dataset.id;
            await deleteComment(commentId);
            await postView(postId);
        }
        if (e.target.classList.contains('edit-comment-btn')) {
            const commentId = e.target.dataset.id;
            const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
            const contentP = commentDiv.querySelector('.comment-content');
            const originalContent = contentP.textContent;
            const editButton = e.target;
            editButton.style.display = 'none';

            document.querySelectorAll('.edit-comment-btn').forEach(btn => {
                btn.disabled = true;
            });

            const textarea = document.createElement('textarea');
            textarea.value = originalContent;
            textarea.classList.add('edit-textarea');

            const saveBtn = document.createElement('button');
            saveBtn.textContent = 'Save';
            saveBtn.classList.add('save-comment-btn');

            contentP.replaceWith(textarea);
            textarea.focus();
            e.target.after(saveBtn);
            let saving = false;

            const reEnableEditBtns = () => {
                document.querySelectorAll('.edit-comment-btn').forEach(btn => {
                    btn.disabled = false;
                });
            }

            const onMouseUp = () => {
                if (saving) {
                    saving = false;
                    textarea.focus();
                }
            };

            saveBtn.addEventListener('mousedown', () => {
                saving = true;
            });

            document.addEventListener('mouseup', onMouseUp)

            textarea.addEventListener('blur', () => {
                if (saving) return;
                textarea.replaceWith(contentP);
                saveBtn.remove();
                editButton.style.display = '';
                reEnableEditBtns();
            });

            saveBtn.addEventListener('click', async () => {
                const newContent = textarea.value;
                const result = await editComment(commentId, newContent);
                if (result.error) return;
                reEnableEditBtns();
                await postView(postId);
            });
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