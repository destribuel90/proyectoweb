const path = window.location.pathname;
const segments = path.split('/');
const productId = segments[2];

fetch(`http://127.0.0.1:8000/api/products/${productId}`)
.then(res => res.json)
.then(res => {
    cos
});