<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarAutenticacion();
require_once __DIR__ . '/../../config/database.php';

$conn = conectarDB();
$metas = $conn->query("SELECT m.*, i.nombre as indicador_nombre, i.codigo FROM metas m JOIN indicadores i ON m.indicador_id=i.id ORDER BY m.fecha_creacion DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metas - <?php echo APP_NAME; ?></title>
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultados/index.php">Resultados</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-trophy"></i> Gestión de Metas</h2>
            <?php if(tienePermiso('gestionar_metas')): ?><a href="crear.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Meta</a><?php endif; ?>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Indicador</th>
                            <th>Meta</th>
                            <th>Umbral Mínimo</th>
                            <th>Periodo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $metas->fetch_assoc()): ?>
                        <tr>
                            <td>[<?php echo $row['codigo']; ?>] <?php echo htmlspecialchars($row['indicador_nombre']); ?></td>
                            <td><strong><?php echo $row['meta_valor']; ?></strong></td>
                            <td><?php echo $row['umbral_minimo']; ?></td>
                            <td><?php echo $row['periodo']; ?></td>
                            <td><span class="badge bg-<?php echo $row['estado']=='vigente'?'success':'secondary'; ?>"><?php echo $row['estado']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
