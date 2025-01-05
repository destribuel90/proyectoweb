document.querySelector('form')
    .addEventListener('submit', e=> {
        e.preventDefault();
        const data = new FormData(e.target);
        const options = {
            method: 'POST',
            body: data
        };
        fetch(URL + '/api/products', options)
        .then(
            window.location.href = URL
        )
        .catch(e => {
            console.log(e);
        });

     });