const token = localStorage.getItem('token');
const userIdFromPath = window.location.pathname.split('/').filter(Boolean).pop();
const message = document.querySelector('#list-message');
const items = document.querySelector('#list-items');

function showMessage(text) {
    message.textContent = text;
    message.hidden = !text;
}

function createPurchaseCard(purchase) {
    const card = document.createElement('article');
    card.className = 'list-card';

    const image = document.createElement('img');
    image.src = `${URL}/storage/img/products/${encodeURIComponent(purchase.image)}`;
    image.alt = purchase.name;
    image.loading = 'lazy';
    card.append(image);

    const details = document.createElement('div');
    details.className = 'list-card-details';

    const name = document.createElement('h3');
    name.textContent = purchase.name;
    details.append(name);

    const price = document.createElement('p');
    price.className = 'list-price';
    price.textContent = `$${Number(purchase.price).toFixed(2)}`;
    details.append(price);

    const seller = document.createElement('p');
    seller.textContent = `Vendedor: ${purchase.seller_name}`;
    details.append(seller);

    const date = document.createElement('p');
    const purchasedAt = new Date(purchase.purchased_at);
    date.textContent = `Fecha: ${Number.isNaN(purchasedAt.getTime())
        ? 'No disponible'
        : purchasedAt.toLocaleString('es-MX')}`;
    details.append(date);

    const view = document.createElement('a');
    view.href = `${URL}/products/${encodeURIComponent(purchase.product_id)}`;
    view.textContent = 'Ver producto';

    card.append(details, view);
    return card;
}

async function loadPurchases() {
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
            showMessage('No tienes permiso para ver estas compras.');
            return;
        }

        const response = await fetch(`${URL}/api/my-purchases`, { headers });
        if (!response.ok) {
            throw new Error('No se pudieron cargar tus compras.');
        }
        const purchases = await response.json();
        items.replaceChildren(...purchases.map(createPurchaseCard));
        showMessage(purchases.length ? '' : 'Aún no has realizado compras.');
    } catch (error) {
        console.error('Error al cargar las compras del usuario:', error);
        showMessage('No fue posible cargar tus compras. Intenta de nuevo más tarde.');
    }
}

loadPurchases();
