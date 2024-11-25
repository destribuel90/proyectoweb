class Products{
    static index(){
        fetch("http://127.0.0.1:8000/api/products")
        .then(response => response.json())
        .then(res =>{
            let content = ``;
            res.forEach(element => {
                content += `
                    <div class="product-card">
                        <img src="http://127.0.0.1:8000/storage/img/products/${element.image}" alt="Producto 1">
                        <div class="product-name">${element.name}</div>
                        <div class="product-price">$${element.price}</div>
                    </div>
                `; 
            }); 
            const containner = document.querySelector('.products');
            containner.innerHTML = content;
        });
    }
    
}
Products.index();