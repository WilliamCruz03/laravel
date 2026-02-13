<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <nav class="nav-farmacia">
        <div>
            <a href="{{ route('ventas.dashboard') }}" style="color: white; text-decoration: none; font-size: 20px; font-weight: bold;">
                <span>VENTAS</span>
            </a>
        </div>

        <!-- Menú de navegación -->
        <div>
            <a href="{{ route('ventas.clientes.index') }}" class="btn btn-light me-2">Catálogo de Clientes</a>
            <a href="{{ route('ventas.articulos.index') }}" class="btn btn-light me-2">Articulos</a>
            <a href="{{ route('ventas.pedidos.index') }}" class="btn btn-light me-2">Pedidos</a>
            <button class="btn btn-light">Administrador</button>
        </div>
    </nav>

    <main class="container mt-4">
        <h1>Dashboard de Ventas</h1>
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>