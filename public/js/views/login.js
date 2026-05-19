import {login} from "../api/auth.js";
import {navigate} from "../navigate.js";
import {setCurrentUser} from "../state.js";

export async function loginView() {
    document.getElementById('navbar').innerHTML = ''

    document.getElementById('app').innerHTML = `
        <div class="auth-container">
            <h1>Twitty Lite</h1>
            <input type="text" id="username" placeholder="Nome de utilizador" />
            <input type="password" id="password" placeholder="Palavra-passe" />
            <p id="error-msg" class="error"></p>
            <button id="login-btn">Login</button>
            <p>Não tens conta? <a href="/register" id="register-link">Regista-te</a></p>
        </div>
    `;

    document.getElementById('login-btn').addEventListener('click', async () => {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const result = await login(username, password);

        if (result.error) {
            document.getElementById('error-msg').textContent = result.error;
            return;
        }

        setCurrentUser(result);
        navigate('/feed');
    });

    document.getElementById('register-link').addEventListener('click', (e) => {
        e.preventDefault();
        navigate('/register')
    });
}