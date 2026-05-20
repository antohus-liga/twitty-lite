import {deletePost, editPost, likePost} from "../api/posts.js";
import {navigate} from "../navigate.js";
import {getCurrentUser} from "../state.js";

export function postTemplate(post) {
    const currentUser = getCurrentUser();
    const isOwner = currentUser && currentUser.username === post.username;

    return `
        <div class="post" data-id="${post.id}">
            <p class="post-timestamp">${dayjs(post.createdAt).fromNow()}</p>
            <a href="/profile/${post.username}" class="post-author">@${post.username}</a>
            <p class="post-content">${post.content}</p>
            <div class="actions">
                <button class="like-btn ${post.isLiked ? 'liked' : ''}" data-id="${post.id}">❤️ ${post.likeCount}</button>
                <button class="comment-btn" data-id="${post.id}">💬 ${post.commentCount}</button>
                ${isOwner ? `
                    <button class="edit-post-btn" data-id="${post.id}">Editar</button>
                    <button class="delete-post-btn" data-id="${post.id}">Eliminar</button>
                ` : ''}
            </div>
        </div>
    `;
}

export function setupPostListeners(containerId, onUpdate) {
    document.getElementById(containerId).addEventListener('click', async (e) => {
        if (e.target.classList.contains('post-author')) {
            e.preventDefault();
            const username = e.target.getAttribute('href').split('/').pop();
            navigate(`/profile/${username}`);
        }
        if (e.target.classList.contains('like-btn')) {
            const postId = e.target.dataset.id;
            await likePost(postId);
            onUpdate();
        }
        if (e.target.classList.contains('comment-btn')) {
            const postId = e.target.dataset.id;
            navigate(`/post/${postId}`);
        }
        if (e.target.classList.contains('delete-post-btn')) {
            const postId = e.target.dataset.id;
            await deletePost(postId);
            onUpdate();
        }
        if (e.target.classList.contains('edit-post-btn')) {
            const postId = e.target.dataset.id;
            const postDiv = document.querySelector(`.post[data-id="${postId}"]`);
            const contentP = postDiv.querySelector('.post-content');
            const originalContent = contentP.textContent;
            const editButton = e.target;
            editButton.style.display = 'none';

            document.querySelectorAll('.edit-post-btn').forEach(btn => {
                btn.disabled = true;
            });

            const textarea = document.createElement('textarea');
            textarea.value = originalContent;
            textarea.classList.add('edit-textarea');

            const saveBtn = document.createElement('button');
            saveBtn.textContent = 'Salvar';
            saveBtn.classList.add('save-post-btn');

            contentP.replaceWith(textarea);
            textarea.focus();
            e.target.after(saveBtn);
            let saving = false;

            const reEnableEditBtns = () => {
                document.querySelectorAll('.edit-post-btn').forEach(btn => {
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
                const result = await editPost(postId, newContent);
                if (result.error) return;
                reEnableEditBtns();
                onUpdate();
            });
        }
    });
}