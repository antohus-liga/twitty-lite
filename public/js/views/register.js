import {register} from "../api/auth.js";
import {navigate} from "../navigate.js";

export async function registerView() {
    document.getElementById('app').innerHTML = `
        <div class="auth-container">
            <h1>Twitty Lite</h1>
            <input type="text" id="username" placeholder="Nome de utilizador" />
            <input type="password" id="password" placeholder="Palavra-passe" />
            <p id="error-msg" class="error"></p>
            <button id="register-btn">Registar</button>
            <p>Já tens uma conta? <a href="/login" id="login-link">Entra</a></p>
        </div>
    `;

    document.getElementById('register-btn').addEventListener('click', async () => {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const result = await register(username, password);

        if (result.error) {
            document.getElementById('error-msg').textContent = result.error;
            return;
        }

        navigate('/login');
    });

    document.getElementById('login-link').addEventListener('click', (e) => {
        e.preventDefault();
        navigate('/login')
    });
}