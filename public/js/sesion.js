document.querySelector('form')
    .addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(e.target);
        const options = {
            method: 'POST',
            body: data
        };

        fetch(URL + '/api/sesion', options)
            .then(response => response.json())
            .then(res => {
                // Verifica si el inicio de sesión fue exitoso
                if (res.message === 'Inicio de sesión completado con éxito') {
                    // Redirige a la página principal si el login fue exitoso
                    window.location.href = URL;
                } else {
                    // Si el inicio de sesión no es exitoso, muestra un mensaje de error
                    alert('Error: ' + res.message); 
                }
            })
            .catch(e => {
                console.log('Error:', e); // Captura y muestra cualquier error
                alert('Hubo un problema con la solicitud');
            });
    });
