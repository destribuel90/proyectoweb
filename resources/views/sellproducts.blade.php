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
                <input type="text" placeholder="Buscar productos..."  id="search-bar-text">
                <button id="search-bar-button">Buscar</button>
            </div>
            <div class="header-buttons">
            <div class="user">
                <img src="icons/user-icon.svg" alt="Icono de usuario" id="imgUser">
                <span><a href="{{ env('APP_URLDES')}}/sesion" id="Iniciar">Iniciar sesión</a></span>
            </div>
            <div class="cart">
                <a href="{{ env('APP_URLDES')}}/cart"><object data="icons/shopping-cart_icon.svg" type="image/svg+xml" width="40px" height="40px" id="icon"></object></a>
                <span id="saldo">$0.00</span>
            </div>
            </div>
        </header>
        <div class="main">
            <!-- Barra de Búsqueda -->


            <!-- Navegación de Categorías -->
            <nav>
                <ul class="menu-horizontal">
                    <li>
                        <a href="#">Categorías</a>
                        <ul class="menu-vertical">
                            <li><a href="curso-html.html">Electronica</a></li>
                            <li><a href="#">Muebles</a></li>
                            <li><a href="#">Autos</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Inscripción</a>
                        <ul class="menu-vertical">
                            <li><a href="#">Anual</a></li>
                            <li><a href="#">Mensual</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="{{ env('APP_URLDES')}}/vender">Vender</a></li>
                </ul>
            </nav>
            
            <div class="products">
                <!-- Aquí van los elementos -->
            </div>
        </div>
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

