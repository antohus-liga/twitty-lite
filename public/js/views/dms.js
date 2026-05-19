import { getMessages, sendMessage } from '../api/messages.js';
import { getCurrentUser } from '../state.js';

export async function dmsView(otherUsername) {
    const messages = await getMessages(otherUsername);
    const currentUser = getCurrentUser();

    document.getElementById('app').innerHTML = `
        <div id="chat">
            <div id="messages">
                ${messages.map(msg => `
                    <div class="message ${msg.senderId === currentUser.id ? 'sent' : 'received'}">
                        <p>${msg.content.trim().replace(/\n/g, '<br>')}</p>
                        <span class="msg-time">${dayjs(msg.createdAt).fromNow()}</span>
                    </div>
                `).join('')}
            </div>
            <div id="send-message">
                <textarea id="message-content" placeholder="Escreve uma mensagem..."></textarea>
                <p id="error-msg" class="error"></p>
                <button id="send-btn">Enviar</button>
            </div>
        </div>
    `;

    document.getElementById('send-btn').addEventListener('click', async () => {
        const btn = document.getElementById('send-btn');
        const content = document.getElementById('message-content').value;
        btn.disabled = true;
        const result = await sendMessage(otherUsername, content);
        if (result.error) {
            document.getElementById('error-msg').textContent = result.error;
            btn.disabled = false;
            return;
        }
        await dmsView(otherUsername);
    });
}