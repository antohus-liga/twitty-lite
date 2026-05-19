import { getConversations } from '../api/messages.js';
import { navigate } from '../navigate.js';

export async function dmsListView() {
    const conversations = await getConversations();

    document.getElementById('app').innerHTML = `
        <div id="conversations">
            <h2>Mensagens</h2>
            ${conversations.length === 0 ? '<p>Sem conversas ainda</p>' : ''}
            ${conversations.map(conv => `
                <div class="conversation" data-id="${conv.otherUserId}">
                    <a href="/dms/${conv.otherUsername}" class="conv-link" data-id="${conv.otherUsername}">
                        @${conv.otherUsername}
                    </a>
                </div>
            `).join('')}
        </div>
    `;

    document.getElementById('conversations').addEventListener('click', (e) => {
        if (e.target.classList.contains('conv-link')) {
            e.preventDefault();
            const otherUsername = e.target.dataset.id;
            navigate(`/dms/${otherUsername}`);
        }
    });
}