<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inicio de sesion</title>
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>
    <form>
        <h1>Iniciar sesión</h1>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Ejemplo: jose@gmail.com" required>
        
        <label for="stock">contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Ejemplo: 1234" required>
        
        <button type="submit">Iniciar sesión</button>

        <p><a href="{{ env('APP_URLDES')}}/registro">¿No tengo cuenta?</a></p>
    </form>
    <script type="module" src="{{ env('APP_URLDES')}}/js/constantes.js"></script>
    <script src="{{ env('APP_URLDES')}}/js/sesion.js"></script>
</body>
</html>