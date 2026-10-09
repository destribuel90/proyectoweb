const API = `${URL}/api`;
const form = document.getElementById('form-editar');
const alerta = document.getElementById('alerta');
const preview = document.getElementById('preview');
let userId = null;

function mostrarAlerta(tipo, texto) {
    alerta.className = `alert alert-${tipo}`;
    alerta.textContent = texto;
}

function limpiarErrores() {
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

async function cargarUsuario() {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = `${URL}/sesion`;
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };
    const ses = await fetch(`${API}/sessionStatus`, { headers });
    if (!ses.ok) {
        if (ses.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user_id');
        }
        window.location.href = `${URL}/sesion`;
        return;
    }
    userId = (await ses.json()).user_id;

    const res = await fetch(`${API}/users/${userId}`, { headers });
    if (!res.ok) {
        throw new Error('No se pudo cargar el usuario.');
    }
    const user = await res.json();

    form.name.value = user.name ?? '';
    form.email.value = user.email ?? '';
    form.birthdate.value = (user.birthdate ?? '').slice(0, 10);
    if (user.image) preview.src = `http://127.0.0.1:8000/storage/img/users/${user.image}`;
}

// Vista previa de la imagen seleccionada
form.image.addEventListener('change', () => {
    const file = form.image.files[0];
    if (file) preview.src = URL.createObjectURL(file);
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    limpiarErrores();
    alerta.className = 'alert d-none';

    const data = new FormData(form);
    if (!data.get('password')) data.delete('password'); // no mandar si está vacía
    if (!form.image.files.length) data.delete('image');

    try {
        const res = await fetch(`${API}/users/${userId}`, {
            method: 'POST',
            body: data,
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        const json = await res.json();

        if (res.status === 422) {
            for (const [campo, msgs] of Object.entries(json.errors)) {
                const input = form.elements[campo];
                if (input) {
                    input.classList.add('is-invalid');
                    input.parentElement.querySelector('.invalid-feedback').textContent = msgs[0];
                }
            }
            mostrarAlerta('danger', 'Revisa los campos marcados.');
        } else if (res.ok) {
            mostrarAlerta('success', json.message);
            form.password.value = '';
        } else {
            mostrarAlerta('danger', json.message ?? 'Error inesperado.');
        }
    } catch {
        mostrarAlerta('danger', 'No se pudo conectar con el servidor.');
    }
});

cargarUsuario();