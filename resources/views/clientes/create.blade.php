<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Crear Nuevo Cliente</span>
            <a href="{{ route('ventas.clientes.index') }}" class="btn btn-light">Volver</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Formulario de Registro</h1>
        <p>Aquí va el formulario para crear clientes.</p>
    </div>
</body>
</html>