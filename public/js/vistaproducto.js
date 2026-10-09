const path = window.location.pathname;
const segments = path.split('/');
const productId = segments[2];
fetch(`${URL}/api/products/${productId}`)
    .then(res => res.json()) // Se corrige invocando res.json()
    .then(data => {
        if (data.price) {
            document.querySelector('#price').textContent = '$' + data.price;
            document.querySelector('#colour').textContent = data.name;
            document.querySelector('#description').textContent = data.description; 
            document.querySelector('#stock').textContent = data.stock;
            document.querySelector('#Vendedor').textContent = data.name_user;
            document.querySelector('.imagen-producto').src = `${URL}/storage/img/products/${data.image}`;
            document.querySelector('#product-user').src = `${URL}/storage/img/users/${data.image_user}`;
            

        } else {
            console.error('El producto no tiene precio o ocurrió un error:', data.message || data);
        }
    })
    .catch(error => {
        console.error('Error al obtener el producto:', error);
    });
const inputQuantity = document.querySelector('.input-quantity');
const btnIncrement = document.querySelector('#increment');
const btnDecrement = document.querySelector('#decrement');

let valueByDefault = parseInt(inputQuantity.value);

// Funciones Click

btnIncrement.addEventListener('click', () => {
	valueByDefault += 1;
	inputQuantity.value = valueByDefault;
});

btnDecrement.addEventListener('click', () => {
	if (valueByDefault === 1) {
		return;
	}
	valueByDefault -= 1;
	inputQuantity.value = valueByDefault;
});
const compra = document.querySelector('.btn-add-to-cart');
compra.addEventListener('click', function(){
    `${URL}/api/products/${productId}`
});


const open = document.getElementById('open');
const modal_container = document.getElementById('modal_container');
const close = document.getElementById('close');
const cerrar = document.getElementById('cerrar');
const verificacion = document.querySelector('#verificacion');

open.addEventListener('click', () => {
    modal_container.classList.add('show');  

});
cerrar.addEventListener('click', ()=>{
    modal_container.classList.remove('show');
})
close.addEventListener('click', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = `${URL}/sesion`;
        return;
    }

    close.disabled = true;
    verificacion.textContent = '';

    try {
        const response = await fetch(`${URL}/api/venta`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ id: productId, stock: inputQuantity.value }),
        });
        const responseText = await response.text();
        let result;

        try {
            result = JSON.parse(responseText);
        } catch {
            throw new Error(`El servidor respondió con un formato inesperado (${response.status}).`);
        }

        if (response.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user_id');
            window.location.href = `${URL}/sesion`;
            return;
        }
        if (!response.ok || !result.status) {
            throw new Error(result.message || 'No se pudo completar la compra.');
        }

        verificacion.textContent = result.message;
        verificacion.style.color = 'green';
        window.location.href = URL;
    } catch (error) {
        console.error('Ocurrió un error al realizar la compra:', error);
        verificacion.textContent = error.message || 'Error de conexión al servidor.';
        verificacion.style.color = 'red';
        close.disabled = false;
    }
});


    
