document.querySelector('form')
    .addEventListener('submit', e=> {
        e.preventDefault();
        const data = new FormData(e.target);
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' 
            },  
            body: data
        };
        fetch('http://127.0.0.1:8000/api/products', options)
        .then(res => res.json)
        .then(resp =>{
            console.log(resp);
        })
        .catch(e => {
            console.log(e);
        })
    });