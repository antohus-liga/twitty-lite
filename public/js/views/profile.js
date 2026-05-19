import {navigate} from "../navigate.js";
import {getUser, updateProfile} from "../api/users.js";
import {postTemplate, setupPostListeners} from "../components/post.js";
import {getCurrentUser} from "../state.js";

export async function profileView(username) {
    const data = await getUser(username);
    const user = getCurrentUser();
    const isOwner = user.username === username;

    if (data.error) {
        document.getElementById('app').innerHTML = '<p>User not found</p>';
        return;
    }

    document.getElementById('app').innerHTML = `
        <div class="profile">
            <h2>@${data.user.username}</h2>
            <p>Member since ${dayjs(data.user.createdAt).format('DD/MM/YYYY')}</p>
            ${isOwner ? '' : `<button id="dm-btn">Send DM</button>`}
            ${isOwner ? `<button id="edit-profile-info">Edit Info</button>` : ''}
            <div id="profile-info">
                <h3 class="user-info">User Info</h3>
                ${data.user.bio ? `
                    <h4 class="info-name">Bio</h4>
                    <p id="bio-content" class="info-content">${data.user.bio}</p>
                ` : ''}
                ${data.user.location ? `
                    <h4 class="info-name">Location</h4>
                    <p id="location-content" class="info-content">${data.user.location}</p>
                ` : ''}
                ${data.user.dateOfBirth ? `
                    <h4 class="info-name">Date of birth</h4>
                    <p id="dateofbirth-content" class="info-content">${data.user.dateOfBirth}</p>
                ` : ''}
                ${data.user.website ? `
                    <h4 class="info-name">Website</h4>
                    <p><a id="website-content" href="${data.user.website}" class="info-content" target="_blank">${data.user.website}</a></p>
                ` : ''}
                ${data.user.occupation ? `
                    <h4 class="info-name">Occupation</h4>
                    <p id="occupation-content" class="info-content">${data.user.occupation}</p>
                ` : ''}
            </div>
        </div>
        <hr>
        <h2>Posts from @${username}</h2>
        <div id="user-posts">
            ${data.posts.map(postTemplate).join('')}
        </div>
    `;
    if (isOwner) {
        document.getElementById('edit-profile-info').addEventListener('click', e => {
            const editBtn = e.target;
            editBtn.style.display = 'none';

            document.getElementById('profile-info').innerHTML = `
            <h3>Edit Info</h3>
            <label>Bio</label>
            <textarea id="edit-bio">${data.user.bio ?? ''}</textarea>
            
            <label>Location</label>
            <input type="text" id="edit-location" list="countries" value="${data.user.location ?? ''}">
            <datalist id="countries">
                <option value="Albania">
                <option value="Andorra">
                <option value="Austria">
                <option value="Belarus">
                <option value="Belgium">
                <option value="Bosnia and Herzegovina">
                <option value="Bulgaria">
                <option value="Croatia">
                <option value="Cyprus">
                <option value="Czech Republic">
                <option value="Denmark">
                <option value="Estonia">
                <option value="Finland">
                <option value="France">
                <option value="Germany">
                <option value="Greece">
                <option value="Hungary">
                <option value="Iceland">
                <option value="Ireland">
                <option value="Italy">
                <option value="Kosovo">
                <option value="Latvia">
                <option value="Liechtenstein">
                <option value="Lithuania">
                <option value="Luxembourg">
                <option value="Malta">
                <option value="Moldova">
                <option value="Monaco">
                <option value="Montenegro">
                <option value="Netherlands">
                <option value="North Macedonia">
                <option value="Norway">
                <option value="Poland">
                <option value="Portugal">
                <option value="Romania">
                <option value="Russia">
                <option value="San Marino">
                <option value="Serbia">
                <option value="Slovakia">
                <option value="Slovenia">
                <option value="Spain">
                <option value="Sweden">
                <option value="Switzerland">
                <option value="Ukraine">
                <option value="United Kingdom">
                <option value="Vatican City">
                <option value="Canada">
                <option value="Mexico">
                <option value="United States">
            </datalist>
            
            <label>Date of Birth</label>
            <input type="date" id="edit-dob" value="${data.user.dateOfBirth ?? ''}">
            
            <label>Website</label>
            <input type="url" id="edit-website" value="${data.user.website ?? ''}">
            
            <label>Occupation</label>
            <select id="edit-occupation">
                <option value="">Select...</option>
                <option value="Student" ${data.user.occupation === 'Student' ? 'selected' : ''}>Student</option>
                <option value="Developer" ${data.user.occupation === 'Developer' ? 'selected' : ''}>Developer</option>
                <option value="Designer" ${data.user.occupation === 'Designer' ? 'selected' : ''}>Designer</option>
                <option value="Teacher" ${data.user.occupation === 'Teacher' ? 'selected' : ''}>Teacher</option>
                <option value="Other" ${data.user.occupation === 'Other' ? 'selected' : ''}>Other</option>
            </select>
            <p id="error-msg" class="error"></p>
        `;

            const saveBtn = document.createElement('button');
            saveBtn.textContent = 'Save';
            saveBtn.classList.add('save-edit-btn');

            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancel';
            cancelBtn.classList.add('cancel-edit-btn');

            editBtn.after(saveBtn);
            saveBtn.after(cancelBtn);

            saveBtn.addEventListener('click', async () => {
                const bio = document.getElementById('edit-bio').value;
                const location = document.getElementById('edit-location').value;
                const dob = document.getElementById('edit-dob').value;
                const website = document.getElementById('edit-website').value;
                const occupation = document.getElementById('edit-occupation').value;

                const result = await updateProfile(bio, location, dob, website, occupation);

                if (result.error) {
                    document.getElementById('error-msg').textContent = result.error;
                    return;
                }

                await profileView(username);
            });

            cancelBtn.addEventListener('click', async () => {
                await profileView(username);
            });
        });
    }

    setupPostListeners('user-posts', () => profileView(data.user.username))

    if (!isOwner) {
        document.getElementById('dm-btn').addEventListener('click', () => {
            navigate(`/dms/${data.user.username}`);
        });
    }
}