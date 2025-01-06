<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAGE</title>
    <link rel="stylesheet" href="css/style.css">
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

            <div class="category">
                <h2>Categorías Populares</h2>
            </div>

            <div class="products">
                <!-- Aquí van los elementos -->
            </div>
        </div>
        <!-- Pie de Página -->
        <footer>
            <p>&copy; 2025 TAGE. Todos los derechos reservados.</p>
        </footer>
        <script type="module" src="js/constantes.js"></script>
        <script type="module" src="js/products.js"></script>
        <script type="module" src="js/dataUser.js"></script>
    </div>
</body>
</html>
