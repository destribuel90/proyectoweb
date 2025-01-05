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
        <h1>Registro</h1>
    
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" placeholder="Ejemplo: Jose Emilio Salvador Hernandez" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Ejemplo: jose@gmail.com" required>
    
        <label for="birthdate">Fecha de nacimiento:</label>
        <input type="date" id="birthdate" name="birthdate"  placeholder="Ejemplo: 15-10-2010" required>
    
        <label for="image">Imagen:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    
        <label for="password">contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Ejemplo: 1234" required>
        
        <button type="submit">Registrarse</button>

        <p><a href="{{ env('APP_URLDES')}}/sesion">¿Ya tengo cuenta?</a></p>
    </form>
    <script type="module" src="{{ env('APP_URLDES')}}/js/constantes.js"></script>
    <script src="{{ env('APP_URLDES')}}/js/registrar.js"></script>
</body>
</html>