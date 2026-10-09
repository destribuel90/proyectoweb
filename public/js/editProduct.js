const API = 'http://127.0.0.1:8000';
const form = document.getElementById('form-producto');
const mensaje = document.getElementById('mensaje');
const preview = document.getElementById('preview');

const token = localStorage.getItem('token');
const productId = new URLSearchParams(window.location.search).get('id');

const headers = {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
};

function mostrar(texto, ok = false) {
    mensaje.textContent = texto;
    mensaje.style.color = ok ? 'green' : 'red';
}

async function cargarProducto() {
    if (!token) {
        window.location.href = `${API}/sesion`;
        return;
    }
    if (!productId) {
        mostrar('Falta el id del producto en la URL (?id=...).');
        return;
    }

    try {
        // Usuario autenticado
        const ses = await fetch(`${API}/api/sessionStatus`, { headers });
        if (ses.status === 401) {
            localStorage.removeItem('token');
            window.location.href = `${API}/sesion`;
            return;
        }
        const { user_id } = await ses.json();

        // Datos del producto
        const res = await fetch(`${API}/api/products/${productId}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) {
            mostrar('El producto no existe.');
            return;
        }
        const product = await res.json();

        // Solo el dueño puede editar (el servidor también lo valida)
        if (product.user_id != user_id) {
            mostrar('No tienes permiso para editar este producto.');
            form.querySelectorAll('input, textarea, button').forEach(el => el.disabled = true);
            return;
        }

        form.name.value = product.name ?? '';
        form.description.value = product.description ?? '';
        form.price.value = product.price ?? '';
        form.stock.value = product.stock ?? '';
        if (product.image) {
            preview.src = `${API}/storage/img/products/${product.image}`;
        }
    } catch (e) {
        console.error(e);
        mostrar('No se pudo conectar con el servidor.');
    }
}

// Vista previa de la nueva imagen (FileReader, para no depender de URL.createObjectURL)
form.image.addEventListener('change', () => {
    const file = form.image.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => (preview.src = reader.result);
    reader.readAsDataURL(file);
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    mensaje.textContent = '';

    const data = new FormData(form);
    if (!form.image.files.length) data.delete('image'); // conserva la imagen actual

    try {
        const res = await fetch(`${API}/api/products/${productId}`, {
            method: 'POST',
            headers,
            body: data
        });
        const json = await res.json();

        if (res.ok) {
            mostrar(json.message, true);
            if (json.productos?.image) {
                preview.src = `${API}/storage/img/products/${json.productos.image}`;
            }
            form.image.value = '';
        } else if (res.status === 400 && json.errors) {
            // Errores de validación de Laravel: { campo: [mensajes] }
            const lista = Object.values(json.errors).flat().join(' ');
            mostrar(lista);
        } else if (res.status === 401) {
            localStorage.removeItem('token');
            window.location.href = `${API}/sesion`;
        } else {
            mostrar(json.message ?? 'Error inesperado.');
        }
    } catch (e) {
        console.error(e);
        mostrar('No se pudo conectar con el servidor.');
    }
});

cargarProducto();