document.querySelector('form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);

    try {
        const response = await fetch(URL + '/api/users', {
            method: 'POST',
            body: data,
        });

        const result = await response.json();

        if (response.status === 201 && result.success) {
            alert(result.message); // Mensaje de éxito
            window.location.href = URL + "/sesion";
        } else if (response.status === 422) {
            alert('Errores de validación: ' + JSON.stringify(result.errors)); // Mensaje de error
        } else {
            alert('Error: ' + result.message); // Mensaje de error general
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error inesperado.');
    }
});
