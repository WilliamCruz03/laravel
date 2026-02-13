<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1" style="color: white; text-decoration: none; font-size: 20px; font-weight: bold;">Crear Nuevo Cliente</span>
            <a href="{{ route('ventas.clientes.index') }}" class="btn btn-light">Volver</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Formulario de Registro</h1>

        <input type="text" class="form-control mb-3" placeholder="Nombre">
        <input type="email" class="form-control mb-3" placeholder="Email">
        <input type="text" class="form-control mb-3" placeholder="Teléfono">
        <input type="text" class="form-control mb-3" placeholder="Dirección">
        <button class="btn btn-success">Guardar Cliente</button>
    </div>
</body>
</html>