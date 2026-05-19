import {navigate} from "../navigate.js";
import {getUser, updateProfile} from "../api/users.js";
import {postTemplate, setupPostListeners} from "../components/post.js";
import {getCurrentUser} from "../state.js";

export async function profileView(username) {
    const data = await getUser(username);
    const user = getCurrentUser();
    const isOwner = user.username === username;

    document.title = `Twitty Lite - @${data.user.username}`;

    if (data.error) {
        document.getElementById('app').innerHTML = '<p>User not found</p>';
        return;
    }

    document.getElementById('app').innerHTML = `
        <div class="profile">
            <h2>@${data.user.username}</h2>
            <p>Membro desde ${dayjs(data.user.createdAt).format('DD/MM/YYYY')}</p>
            ${isOwner ? '' : `<button id="dm-btn">Enviar Mensagem</button>`}
            ${isOwner ? `<button id="edit-profile-info">Editar perfil</button>` : ''}
            <div id="profile-info">
                <h3 class="user-info">Perfil</h3>
                ${data.user.bio ? `
                    <h4 class="info-name">Bio</h4>
                    <p id="bio-content" class="info-content">${data.user.bio}</p>
                ` : ''}
                ${data.user.location ? `
                    <h4 class="info-name">Localização</h4>
                    <p id="location-content" class="info-content">${data.user.location}</p>
                ` : ''}
                ${data.user.dateOfBirth ? `
                    <h4 class="info-name">Data de Nascimento</h4>
                    <p id="dateofbirth-content" class="info-content">${data.user.dateOfBirth}</p>
                ` : ''}
                ${data.user.website ? `
                    <h4 class="info-name">Website</h4>
                    <p><a id="website-content" href="${data.user.website}" class="info-content" target="_blank">${data.user.website}</a></p>
                ` : ''}
                ${data.user.occupation ? `
                    <h4 class="info-name">Profissão</h4>
                    <p id="occupation-content" class="info-content">${data.user.occupation}</p>
                ` : ''}
            </div>
        </div>
        <hr>
        <h2>Publicações de @${username}</h2>
        <div id="user-posts">
            ${data.posts.map(postTemplate).join('')}
        </div>
    `;
    if (isOwner) {
        document.getElementById('edit-profile-info').addEventListener('click', e => {
            const editBtn = e.target;
            editBtn.style.display = 'none';

            document.getElementById('profile-info').innerHTML = `
            <h3>Editar Perfil</h3>
            <label>Bio</label>
            <textarea id="edit-bio">${data.user.bio ?? ''}</textarea>
            
            <label>Localização</label>
            <input type="text" id="edit-location" list="countries" value="${data.user.location ?? ''}">
            <datalist id="countries">
                <option value="Albânia">
                <option value="Andorra">
                <option value="Áustria">
                <option value="Bielorrússia">
                <option value="Bélgica">
                <option value="Bósnia e Herzegovina">
                <option value="Bulgária">
                <option value="Croácia">
                <option value="Chipre">
                <option value="República Checa">
                <option value="Dinamarca">
                <option value="Estónia">
                <option value="Finlândia">
                <option value="França">
                <option value="Alemanha">
                <option value="Grécia">
                <option value="Hungria">
                <option value="Islândia">
                <option value="Irlanda">
                <option value="Itália">
                <option value="Kosovo">
                <option value="Letónia">
                <option value="Listenstaine">
                <option value="Lituânia">
                <option value="Luxemburgo">
                <option value="Malta">
                <option value="Moldávia">
                <option value="Mónaco">
                <option value="Montenegro">
                <option value="Países Baixos">
                <option value="Macedónia do Norte">
                <option value="Noruega">
                <option value="Polónia">
                <option value="Portugal">
                <option value="Roménia">
                <option value="Rússia">
                <option value="São Marinho">
                <option value="Sérvia">
                <option value="Eslováquia">
                <option value="Eslovénia">
                <option value="Espanha">
                <option value="Suécia">
                <option value="Suíça">
                <option value="Ucrânia">
                <option value="Reino Unido">
                <option value="Cidade do Vaticano">
                <option value="Canadá">
                <option value="México">
                <option value="Estados Unidos">
            </datalist>
            
            <label>Data de Nascimento</label>
            <input type="date" id="edit-dob" value="${data.user.dateOfBirth ?? ''}">
            
            <label>Website</label>
            <input type="url" id="edit-website" value="${data.user.website ?? ''}">
            
            <label>Profissão</label>
            <select id="edit-occupation">
                <option value="">Selecione...</option>
                <option value="Student" ${data.user.occupation === 'Estudante' ? 'selected' : ''}>Estudante</option>
                <option value="Developer" ${data.user.occupation === 'Developer' ? 'selected' : ''}>Developer</option>
                <option value="Designer" ${data.user.occupation === 'Designer' ? 'selected' : ''}>Designer</option>
                <option value="Teacher" ${data.user.occupation === 'Professor' ? 'selected' : ''}>Professor</option>
                <option value="Other" ${data.user.occupation === 'Outra' ? 'selected' : ''}>Outra</option>
            </select>
            <p id="error-msg" class="error"></p>
        `;

            const saveBtn = document.createElement('button');
            saveBtn.textContent = 'Salvar';
            saveBtn.classList.add('save-edit-btn');

            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancelar';
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