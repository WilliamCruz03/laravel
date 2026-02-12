<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="nav-farmacia">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Módulo de Clientes</span>
            <div>
                <a href="{{ route('ventas.clientes.create') }}" class="btn btn-success me-2">Nuevo Cliente</a>
                <a href="{{ route('ventas.dashboard') }}" class="btn btn-light">Dashboard</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Listado de Clientes</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Pedro Pérez</td>
                    <td>Pedro@email.com</td>
                    <td>
                        
                        <button class="btn btn-sm btn-primary">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Juan García</td>
                    <td>Juan@email.com</td>
                    <td>
                        
                        <button class="btn btn-sm btn-primary">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>