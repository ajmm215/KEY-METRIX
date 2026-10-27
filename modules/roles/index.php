<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarPermiso('gestionar_roles');
require_once __DIR__ . '/../../config/database.php';

$conn = conectarDB();
$roles = $conn->query("SELECT * FROM roles ORDER BY id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivrijs.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard/index.php"><?php echo APP_NAME; ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="../dashboard/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../usuarios/index.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php">Roles</a></li>
                    <li class="nav-item"><a class="nav-link" href="../indicadores/index.php">Indicadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../metas/index.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultados/index.php">Resultados</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-shield-lock"></i> Gestión de Roles y Permisos</h2>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $roles->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><span class="badge bg-<?php echo $row['estado']=='activo'?'success':'danger'; ?>"><?php echo $row['estado']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
