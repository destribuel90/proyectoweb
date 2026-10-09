const Avatar = document.querySelector('#avatar');
const Title = document.querySelector('.titulo');
const Sala = document.querySelector('#sala');
const iniciar = document.querySelector('#Iniciar');
const token = localStorage.getItem('token');

async function verificarSesion() {
    if (!token) {
        iniciar.textContent = 'Iniciar sesión';
        return;
    }

    try {
        const headers = {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
        };
        const res = await fetch(`${URL}/api/sessionStatus`, { headers });
        if (!res.ok) {
            if (res.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user_id');
                iniciar.textContent = 'Iniciar sesión';
                return;
            }
            throw new Error('No se pudo verificar la sesión');
        }
        const response = await res.json();

        if (response.active) {
            const data = await fetch(`${URL}/api/users/${response.user_id}`, { headers });
            if (!data.ok) {
                throw new Error('No se pudo obtener los datos del usuario');
            }
            const userData = await data.json();

            Title.textContent = userData.name;
            Avatar.src = `${URL}/storage/img/users/${userData.image}`;
            Sala.value = Number(userData.current_balance).toFixed(2);
            iniciar.textContent = userData.name;
            iniciar.href = `${URL}/perfil`;

            document.querySelector('#btn-editar').href = `${URL}/editar`;
            document.querySelector('#link-productos').href = `${URL}/mis-productos/${response.user_id}`;
            document.querySelector('#link-compras').href = `${URL}/mis-compras/${response.user_id}`;
        }
    } catch (error) {
        console.error('Error al verificar la sesión o al obtener datos del usuario:', error);
        iniciar.textContent = 'Iniciar sesión';
    }
}

verificarSesion();



document.querySelector('.btn-cerrar-sesion').addEventListener('click', function () {
    const options = {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
    };

    fetch(`${URL}/api/logout`, options)
        .then(res => res.json())
        .then(res => {
            if (res.logout) {
                localStorage.removeItem('token');
                localStorage.removeItem('user_id');
                window.location.href = URL; // Redirige al usuario a la página principal
            } else {
                console.error('Error al cerrar sesión:', res.message);
            }
        })
        .catch(e => {
            console.error('Error en la solicitud:', e);
            alert('Ocurrió un error al intentar cerrar la sesión.');
        });
});
