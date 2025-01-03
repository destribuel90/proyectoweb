class Products{
    static index(){
        fetch("http://127.0.0.1:8000/api/products")
        .then(response => response.json())
        .then(res =>{
            let content = ``;
            res.forEach(element => {
                content += `
                    <a href="http://127.0.0.1:8000/products/${element.id}">
                        <div class="product-card">
                            <img src="http://127.0.0.1:8000/storage/img/products/${element.image}" alt="Producto 1">
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
Products.index();
const svg = document.querySelector('#text');
svg.textContent = "2";