import {navigate} from "../navigate.js";
import {getUser} from "../api/users.js";

export async function profileView(username) {
    const data = await getUser(username);

    if (data.error) {
        document.getElementById('app').innerHTML = '<p>User not found</p>';
        return;
    }

    document.getElementById('app').innerHTML = `
        <div class="profile">
            <h2>@${data.user.username}</h2>
            <p>Member since ${dayjs(data.user.createdAt).format('DD/MM/YYYY')}</p>
            <button id="dm-btn">Send DM</button>
        </div>
        <div id="user-posts">
            ${data.posts.map(post => `
                <div class="post">
                    <p class="post-content">${post.content}</p>
                    <span>❤️ ${post.likeCount}</span>
                </div>
            `).join('')}
        </div>
    `;

    document.getElementById('dm-btn').addEventListener('click', () => {
        navigate(`/dms/${data.user.username}`);
    });
}