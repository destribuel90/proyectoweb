const iniciar = document.querySelector('#Iniciar');
const img = document.querySelector('#imgUser');
const saldo = document.querySelector('#saldo');

async function verificarSesion() {
    const token = localStorage.getItem('token');

    // Sin token no hay sesión: no hace falta llamar a la API
    if (!token) {
        iniciar.textContent = 'Iniciar sesión';
        return;
    }

    try {
        iniciar.textContent = 'Verificando sesión...';

        const res = await fetch(`${URL}/api/sessionStatus`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (res.status === 401) {
            // Token inválido o expirado
            localStorage.removeItem('token');
            localStorage.removeItem('user_id');
            iniciar.textContent = 'Iniciar sesión';
            return;
        }
        if (!res.ok) {
            throw new Error('No se pudo verificar la sesión');
        }

        const response = await res.json();

        if (response.active) {
            const data = await fetch(`${URL}/api/users/${response.user_id}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!data.ok) {
                throw new Error('No se pudo obtener los datos del usuario');
            }
            const Userdata = await data.json();

            iniciar.textContent = Userdata.name;
            iniciar.href = URL + '/perfil';
            if (Userdata.image) {
                img.src = `${URL}/storage/img/users/${Userdata.image}`;
            }
            saldo.textContent = '$' + parseFloat(Userdata.current_balance).toFixed(2);
        } else {
            iniciar.textContent = 'Iniciar sesión';
        }
    } catch (error) {
        console.error('Error al verificar la sesión o al obtener datos del usuario:', error);
        iniciar.textContent = 'Iniciar sesión';
    }
}

verificarSesion();