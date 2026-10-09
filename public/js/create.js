const form = document.querySelector('form');
const message = document.querySelector('#publish-message');
const token = localStorage.getItem('token');

function showMessage(text, isError = false) {
    message.textContent = text;
    message.classList.toggle('error', isError);
    message.classList.toggle('success', !isError);
}

if (!token) {
    window.location.href = `${URL}/sesion`;
} else {
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        showMessage('Publicando producto...');

        try {
            const response = await fetch(`${URL}/api/products`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: new FormData(form),
            });
            const result = await response.json();

            if (response.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user_id');
                window.location.href = `${URL}/sesion`;
                return;
            }
            if (!response.ok) {
                const validationMessages = result.errors
                    ? Object.values(result.errors).flat().join(' ')
                    : result.message;
                throw new Error(validationMessages || 'No se pudo publicar el producto.');
            }

            showMessage(result.message, false);
            form.reset();
        } catch (error) {
            console.error('Error al publicar el producto:', error);
            showMessage(error.message || 'No fue posible conectar con el servidor.', true);
        } finally {
            submitButton.disabled = false;
        }
    });
}