<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarAutenticacion();
require_once __DIR__ . '/../../config/database.php';

$conn = conectarDB();
$auditoria = $conn->query("SELECT a.*, u.nombre_completo, u.usuario FROM auditoria a LEFT JOIN usuarios u ON a.usuario_id=u.id ORDER BY a.fecha DESC LIMIT 100");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard/index.php"><?php echo APP_NAME; ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="../dashboard/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../usuarios/index.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="../indicadores/index.php">Indicadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../metas/index.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultados/index.php">Resultados</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php">Auditoría</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="mb-4"><i class="bi bi-shield-check"></i> Auditoría y Trazabilidad</h2>
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Registro de Acciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $auditoria->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($row['fecha'])); ?></td>
                                <td><?php echo $row['nombre_completo'] ? htmlspecialchars($row['nombre_completo']) . ' (' . $row['usuario'] . ')' : 'Sistema'; ?></td>
                                <td><?php echo htmlspecialchars($row['accion']); ?></td>
                                <td><?php echo $row['ip']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
