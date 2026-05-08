import {register} from "../api/auth.js";
import {navigate} from "../navigate.js";

export async function registerView() {
    document.getElementById('app').innerHTML = `
        <div class="auth-container">
            <h1>Twitty Lite</h1>
            <input type="text" id="username" placeholder="Username" />
            <input type="password" id="password" placeholder="Password" />
            <button id="register-btn">Register</button>
            <p>Already have an account? <a href="/login" id="login-link">Login</a></p>
        </div>
    `;

    document.getElementById('register-btn').addEventListener('click', async () => {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const result = await register(username, password);

        if (result.error) {
            alert(result.error);
            return;
        }

        navigate('/login');
    });

    document.getElementById('login-link').addEventListener('click', (e) => {
        e.preventDefault();
        navigate('/login')
    });
}