<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-database"></i> <?= $title ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Conectividad de Base de Datos -->
                        <div class="mb-4">
                            <h5>Estado de Conexión</h5>
                            <?php if ($db_connected): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> 
                                    Conexión a la base de datos exitosa
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle"></i> 
                                    Error de conexión a la base de datos
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Error -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <h6>Error:</h6>
                                <code><?= esc($error) ?></code>
                            </div>
                        <?php endif; ?>

                        <!-- Estado de Tablas -->
                        <div class="mb-4">
                            <h5>Estado de Tablas</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tabla</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tables_exist as $table => $exists): ?>
                                            <tr>
                                                <td><code><?= $table ?></code></td>
                                                <td>
                                                    <?php if ($exists): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i> Existe
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times"></i> No existe
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Héroes de Muestra -->
                        <?php if (!empty($sample_heroes)): ?>
                            <div class="mb-4">
                                <h5>Héroes de Muestra (Primeros 5)</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Alias</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sample_heroes as $hero): ?>
                                                <tr>
                                                    <td><?= $hero['id'] ?></td>
                                                    <td><?= esc($hero['name'] ?? 'N/A') ?></td>
                                                    <td><?= esc($hero['alias'] ?? 'N/A') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Botones de Prueba -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button id="testSearchBtn" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i> Probar Búsqueda
                            </button>
                            <a href="/hero" class="btn btn-primary">
                                <i class="fas fa-rocket"></i> Ir al Sistema de Búsqueda
                            </a>
                        </div>

                        <!-- Resultado de Prueba -->
                        <div id="testResult" class="mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('testSearchBtn').addEventListener('click', async function() {
            const btn = this;
            const resultDiv = document.getElementById('testResult');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Probando...';
            
            try {
                const response = await fetch('/test/search');
                const data = await response.json();
                
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <h6>✅ Prueba de Búsqueda Exitosa</h6>
                            <p>Se encontraron <strong>${data.count}</strong> héroes con el término "bat"</p>
                            <details>
                                <summary>Ver resultados</summary>
                                <pre class="mt-2">${JSON.stringify(data.heroes, null, 2)}</pre>
                            </details>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h6>❌ Error en la Prueba</h6>
                            <p>${data.error}</p>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6>❌ Error de Conexión</h6>
                        <p>${error.message}</p>
                    </div>
                `;
            }
            
            resultDiv.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Probar Búsqueda';
        });
    </script>
</body>
</html>
