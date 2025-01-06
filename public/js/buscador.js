const searchButt = document.querySelector('#search-bar-button');
const searchText = document.querySelector('#search-bar-text');
searchButt.addEventListener('click', function () {
    let data = searchText.value.trim(); 
    if (data) { 
        data = data.replace(/\s+/g, '-'); 
        window.location.href = `${URL}/search/${encodeURIComponent(data)}`;
    } else {
        alert('Por favor, ingresa un término de búsqueda.'); 
    }
});
searchText.addEventListener('keypress', function(event){
    if(event.key === 'Enter'){
        searchButt.click();
    }
})