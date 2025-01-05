const Avatar = document.querySelector('#avatar');
const Title = document.querySelector('.titulo');
async function verificarSesion() {
    try {

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
            Title.textContent = Userdata.name;
            Avatar.src = `${URL}/storage/img/users/${Userdata.image}`;

        } else {
            iniciar.textContent = 'Iniciar sesión'; // Si la sesión no está activa
        }
    } catch (error) {
        console.error('Error al verificar la sesión o al obtener datos del usuario:', error);
        iniciar.textContent = 'Iniciar Sesion'; // Mostrar mensaje de error
    }
}

verificarSesion();
