import {getLeaderboard} from "../api/leaderboard.js";

export async function leaderboardView() {
    const leaderboard = await getLeaderboard();

    document.getElementById('app').innerHTML = `
        <table>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Total post likes received</th>
                <th>Total comment likes received</th>
                <th>Total posts made</th>
                <th>Total comments made</th>
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