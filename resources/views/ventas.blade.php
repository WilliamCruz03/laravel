<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

    <nav class="nav-farmacia"">
        <div>
            <a href="#" style="color: white; text-decoration: none; font-size: 20px; font-weight: bold;>
                <span style="color: #3498db;">VENTAS</span>
            </a>
        </div>

        <!-- Botones -->
         <div>
         <button id="btn-abrir-modal-cliente" class="btn btn-light me-2">Catalogo de Clientes</button>
         <button id="btn-abrir-modal-cotizaciones" class="btn btn-light me-2">Cotizaciones</button>
         <button id="btn-abrir-modal-ofertas" class="btn btn-light me-2">Ofertas</button>
         <button id="btn-abrir-modal-admin" class="btn btn-light me-2">Administrador</button>
         </div>

         <div>
            <ul>
                <li><a href="#">Configuracion</a></li>
                <li><a href="#">Cerrar sesion</a></li>
            </ul>
         </div>
    </nav>

         <!-- Contenido principal -->
    <main class="container mt-4">
        <h1>Dashboard de Ventas</h1>
        <p>Selecciona una opción del menú superior</p>
    </main>

    <!-- Modal para Catálogo de Clientes -->
    <div class="modal fade" id="modalClientes" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Catálogo de Clientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Catálogo de clientes aquí.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal para Catálogo de Cotizaciones -->
    <div class="modal fade" id="modalCotizaciones" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cotizaciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Cotizaciones.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal para Catálogo de Ofertas -->
    <div class="modal fade" id="modalOfertas" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ofertas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Ofertas</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-body">
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none">Configuración</a></li>
                        <li><a href="#" class="text-decoration-none">Cerrar sesión</a></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

</body>
</html>