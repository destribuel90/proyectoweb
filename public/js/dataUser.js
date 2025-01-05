const iniciar = document.querySelector('#Iniciar');
const img = document.querySelector('#imgUser');
const saldo = document.querySelector('#saldo');


async function verificarSesion() {
    try {
        iniciar.textContent = 'Verificando sesión...'; // Mostrar mensaje de carga

        // Verificar si la sesión está activa
        const res = await fetch(URL + '/api/sessionStatus');
        if (!res.ok) {
            throw new Error('No se pudo verificar la sesión');
        }
        const response = await res.json();

        if (response.active) {
            // Obtener los datos del usuario
            const data = await fetch(`${URL}/api/users/${response.user_id}`);
            if (!data.ok) {
                throw new Error('No se pudo obtener los datos del usuario');
            }
            const Userdata = await data.json();

            // Mostrar el nombre del usuario
            iniciar.textContent = Userdata.name;
            img.src = `${URL}/storage/img/users/${Userdata.image}`;
            saldo.textContent = '$' + parseFloat(Userdata.current_balance).toFixed(2);

        } else {
            iniciar.textContent = 'Iniciar sesión'; // Si la sesión no está activa
        }
    } catch (error) {
        console.error('Error al verificar la sesión o al obtener datos del usuario:', error);
        iniciar.textContent = 'Iniciar Sesion'; // Mostrar mensaje de error
    }
}

verificarSesion();
