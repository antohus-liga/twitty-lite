import {getLeaderboard} from "../api/leaderboard.js";
import {navigate} from "../navigate.js";

export async function leaderboardView() {
    const leaderboard = await getLeaderboard();

    document.getElementById('app').innerHTML = `
        <table>
            <tr>
                <th>Classificação</th>
                <th>Utilizador</th>
                <th>Likes nas publicações</th>
                <th>Likes nos comentários</th>
                <th>Publicações</th>
                <th>Comentários</th>
            </tr>
            ${leaderboard.map((userStats, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><a href="/profile/${userStats.username}" class="leaderboard-username">${userStats.username}</a></td>
                <td>${userStats.totalPostLikes}</td>
                <td>${userStats.totalCommentLikes}</td>
                <td>${userStats.totalPosts}</td>
                <td>${userStats.totalComments}</td>
            </tr>
            `).join('')}
        </table>
    `;

    document.querySelector('table').addEventListener('click', (e) => {
        if (e.target.classList.contains('leaderboard-username')) {
            e.preventDefault();
            navigate(`/profile/${e.target.textContent}`);
        }
    });
}