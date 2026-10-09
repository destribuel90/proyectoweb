const token = localStorage.getItem('token');
const userIdFromPath = window.location.pathname.split('/').filter(Boolean).pop();
const message = document.querySelector('#list-message');
const items = document.querySelector('#list-items');

function showMessage(text) {
    message.textContent = text;
    message.hidden = !text;
}

function createProductCard(product) {
    const card = document.createElement('article');
    card.className = 'list-card';

    const image = document.createElement('img');
    image.src = `${URL}/storage/img/products/${encodeURIComponent(product.image)}`;
    image.alt = product.name;
    image.loading = 'lazy';
    card.append(image);

    const details = document.createElement('div');
    details.className = 'list-card-details';

    const name = document.createElement('h3');
    name.textContent = product.name;
    details.append(name);

    const price = document.createElement('p');
    price.className = 'list-price';
    price.textContent = `$${Number(product.price).toFixed(2)}`;
    details.append(price);

    const stock = document.createElement('p');
    stock.textContent = `Existencias: ${product.stock}`;
    details.append(stock);

    const actions = document.createElement('div');
    actions.className = 'list-card-actions';

    const view = document.createElement('a');
    view.href = `${URL}/products/${encodeURIComponent(product.id)}`;
    view.textContent = 'Ver producto';
    actions.append(view);

    const edit = document.createElement('a');
    edit.href = `${URL}/editar_producto?id=${encodeURIComponent(product.id)}`;
    edit.textContent = 'Editar';
    actions.append(edit);

    const remove = document.createElement('button');
    remove.className = 'list-delete-button';
    remove.type = 'button';
    remove.textContent = 'Borrar';
    remove.addEventListener('click', () => deleteProduct(product, card, remove));
    actions.append(remove);

    card.append(details, actions);
    return card;
}

async function deleteProduct(product, card, button) {
    if (!window.confirm(`¿Quieres borrar "${product.name}"?`)) {
        return;
    }

    button.disabled = true;

    try {
        const response = await fetch(`${URL}/api/products/${encodeURIComponent(product.id)}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
        });

        const result = await response.json();
        if (response.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user_id');
            window.location.href = `${URL}/sesion`;
            return;
        }
        if (!response.ok) {
            throw new Error(result.message || 'No se pudo borrar el producto.');
        }

        card.remove();
        if (!items.children.length) {
            showMessage('Aún no has publicado productos.');
        }
    } catch (error) {
        console.error('Error al borrar el producto:', error);
        showMessage(error.message || 'No fue posible borrar el producto.');
        button.disabled = false;
    }
}

async function loadProducts() {
    if (!token) {
        window.location.href = `${URL}/sesion`;
        return;
    }

    if (!/^\d+$/.test(userIdFromPath)) {
        showMessage('No se encontró el usuario solicitado.');
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
    };

    try {
        const sessionResponse = await fetch(`${URL}/api/sessionStatus`, { headers });
        if (sessionResponse.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user_id');
            window.location.href = `${URL}/sesion`;
            return;
        }
        if (!sessionResponse.ok) {
            throw new Error('No se pudo verificar la sesión.');
        }

        const session = await sessionResponse.json();
        if (String(session.user_id) !== userIdFromPath) {
            showMessage('No tienes permiso para ver estos productos.');
            return;
        }

        const response = await fetch(`${URL}/api/my-products`, { headers });
        if (!response.ok) {
            throw new Error('No se pudieron cargar tus productos.');
        }
        const products = await response.json();
        items.replaceChildren(...products.map(createProductCard));
        showMessage(products.length ? '' : 'Aún no has publicado productos.');
    } catch (error) {
        console.error('Error al cargar los productos del usuario:', error);
        showMessage('No fue posible cargar tus productos. Intenta de nuevo más tarde.');
    }
}

loadProducts();
