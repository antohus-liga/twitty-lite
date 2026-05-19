import {getLeaderboard} from "../api/leaderboard.js";

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
                <td>${userStats.username}</td>
                <td>${userStats.totalPostLikes}</td>
                <td>${userStats.totalCommentLikes}</td>
                <td>${userStats.totalPosts}</td>
                <td>${userStats.totalComments}</td>
            </tr>
            `).join('')}
        </table>
    `;
}