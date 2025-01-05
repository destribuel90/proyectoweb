<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAGE</title>
    <link rel="stylesheet" href="css/sellstyle.css">
</head>
<body>
    <div class="container">
        <header>
            <a href="{{ env('APP_URLDES')}}/"><h1 class="header__centered">TAGE</h1></a>
            <div class="search-bar">
                <input type="text" placeholder="Buscar productos...">
                <button>Buscar</button>
            </div>
            <div class="header-buttons">
            <div class="user">
                <img src="icons/user-icon.svg" alt="Icono de usuario">
                <span><a href="">Iniciar sesión</a></span>
            </div>
            <div class="cart">
                <img src="icons/shopping-cart_icon.svg" alt="Icono de carrito">
                <span>0</span>
                <span>$0.00</span>
            </div>
            </div>
        </header>
        <div class="main">
            <nav>
                <a href="#">Categorías</a>
                <a href="#">Lo más vendido</a>
                <a href="{{ env('APP_URLDES')}}/productCreate">Vender</a>
                <a href="#">Despensa</a>
                <a href="#">Juguetes</a>
                <a href="#">Moda</a>
            </nav>
            <form>
                <h1>Publicar Producto</h1>
            
                <!-- Nombre del producto -->
                <label for="name">Nombre del producto:</label>
                <input type="text" id="name" name="name" placeholder="Ejemplo: Camiseta" required>
            
                <!-- Descripción del producto -->
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" placeholder="Describe el producto..." required></textarea>
            
                <!-- Precio del producto -->
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" placeholder="Ejemplo: 19.99" required>
            
                <!-- Stock del producto -->
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" min="0" placeholder="Ejemplo: 100" required>
            
                <!-- URL de la imagen -->
                <label for="image">Imagen:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            
                <!-- Botón de envío -->
                <button type="submit">Guardar Producto</button>
            </form>
                        
            <div id="containnerImage"></div>
        </div>
        <script type="module" src="{{ env('APP_URLDES')}}/js/constantes.js"></script>
        <script src="{{ env('APP_URLDES')}}/js/create.js"></script>
</body>
</html>
