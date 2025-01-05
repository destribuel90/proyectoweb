<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en Línea</title>
    <link rel="stylesheet" href="{{ env('APP_URLDES')}}/css/style.css">
    <link rel="stylesheet" href="{{ env('APP_URLDES')}}/css/productos.css">

</head>
<body>
    <div class="container">
        <header>
            <a href="{{ env('APP_URLDES')}}/"><h1 class="header__centered">TAGE</h1></a>
            <div class="search-bar">
                <input type="text" placeholder="Buscar productos..." id="search-bar-text">
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

            <div class="main_content">
                <!-- Producto 1 -->
                <main>
                    <div class="container-img">
                        <img
                            src=""
                            alt="imagen-producto"
                            class="imagen-producto"
                        />
                    </div>
                    <div class="container-info-product">
                        <div class="container-price">
                            <span id="price"></span>
                            <i class="fa-solid fa-angle-right"></i>
                        </div>

                        <div class="container-details-product">
                            <div class="form-group">
                                <div id="colour">
                                    YEYIAN Gabinete Gamer Lancer con Ventana, Midi-Tower, ATX, Micro ATX, Mini ITX, Panel Malla y Cristal Templado,
                                </div>
                            </div>
                        
                        </div>

                        <div class="container-add-cart">
                            <div class="container-quantity">
                                <input
                                    type="number"
                                    placeholder="1"
                                    value="1"
                                    min="1"
                                    class="input-quantity"
                                />
                                <div class="btn-increment-decrement">
                                    <i class="fa-solid fa-chevron-up" id="increment"></i>
                                    <i class="fa-solid fa-chevron-down" id="decrement"></i>
                                </div>
                            </div>
                            <button class="btn-add-to-cart">
                                Comprar
                            </button>
                        </div>

                        <div class="container-description">
                            <div class="title-description">
                                <h4>Descripción</h4>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="text-description">
                                <p id="description">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laboriosam iure provident atque voluptatibus reiciendis quae rerum, maxime placeat enim cupiditate voluptatum, temporibus quis iusto. Enim eum qui delectus deleniti similique? Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sint autem magni earum est dolorem saepe perferendis repellat ipsam laudantium cum assumenda quidem quam, vero similique? Iusto officiis quod blanditiis iste?
                                </p>
                            </div>
                        </div>


                        <div class="container-reviews">
                            <div class="title-reviews">
                                <h4>Stock:    <span id="stock">10</span></h4>
                            </div>
                            <div class="text-reviews hidden">
                                <p>-----------</p>
                            </div>
                        </div>

                        <div class="container-social">
                            <span>Vendedor:  <span id="Vendedor">Paco</span> <img src="" alt="image" id="product-user"></span>
  
                        </div>
                    </div>
                </main>

                <!-- Aquí puedes agregar más productos -->
            </div>
        </div>

        <!-- Pie de Página -->
        <footer>
            <p>&copy; 2025 Tienda en Línea. Todos los derechos reservados.</p>
        </footer>
        
    </div>
    <script type="module" src="{{ env('APP_URLDES')}}/js/constantes.js"></script>
    <script type="module" src="{{ env('APP_URLDES')}}/js/vistaproducto.js"></script>
    <script type="module" src="{{ env('APP_URLDES')}}/js/dataUser.js"></script>
    <script
        src="https://kit.fontawesome.com/81581fb069.js"
        crossorigin="anonymous"
    ></script>
</body>
</html>
