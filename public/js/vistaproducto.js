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

const compra = document.querySelector('.btn-add-to-cart');
compra.addEventListener('click', function(){
    `${URL}/api/products/${productId}`
});

    
