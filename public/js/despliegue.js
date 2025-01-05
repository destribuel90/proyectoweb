export class Despliegue{
    index(ruta){
    fetch(`${URL}/api/${encodeURIComponent(ruta)}`)
    .then(response => response.json())
    .then(res =>{
        let content = ``;
        res.forEach(element => {
            content += `
                <a href="${URL}/products/${element.id}">
                    <div class="product-card">
                        <img src="${URL}/storage/img/products/${element.image}" alt="Producto 1">
                        <div class="product-name">${element.name}</div>
                        <div class="product-price">$${element.price}</div>
                    </div>
                </a>
            `; 
        }); 
        const containner = document.querySelector('.products');
        containner.innerHTML = content;
    });
}

}