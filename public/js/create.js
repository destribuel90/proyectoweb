document.querySelector('form')
    .addEventListener('submit', e=> {
        e.preventDefault();
        const data = new FormData(e.target);
        const options = {
            method: 'POST',
            body: data
        };
        fetch('http://127.0.0.1:8000/api/products', options)
    .then(res => res.json())
    .then(resp => {
        const data = resp.productos;
        console.log(data.image); 

        if(data.image) { 
            const imageContainer = document.querySelector('#containnerImage');
            imageContainer.innerHTML = `
                <img src="http://127.0.0.1:8000/storage/img/products/${data.image}" alt="Imagen del producto">
            `;
        } else {
            console.log('No se encontró la imagen');
        }
    })
    .catch(e => {
        console.log(e);
    });

    });