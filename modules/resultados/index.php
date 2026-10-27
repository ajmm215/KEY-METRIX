<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarAutenticacion();
require_once __DIR__ . '/../../config/database.php';

$conn = conectarDB();
$resultados = $conn->query("SELECT r.*, i.nombre as indicador_nombre, i.codigo, u.nombre_completo as registrado_por_nombre FROM resultados r JOIN indicadores i ON r.indicador_id=i.id LEFT JOIN usuarios u ON r.registrado_por=u.id ORDER BY r.fecha_registro DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - <?php echo APP_NAME; ?></title>
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Resultados</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-clipboard-data"></i> Registro de Resultados</h2>
            <?php if(tienePermiso('registrar_resultados')): ?><a href="crear.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Registrar Resultado</a><?php endif; ?>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Indicador</th>
                            <th>Periodo</th>
                            <th>Valor Real</th>
                            <th>Registrado Por</th>
                            <th>Fecha Registro</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultados->fetch_assoc()): ?>
                        <tr>
                            <td>[<?php echo $row['codigo']; ?>] <?php echo htmlspecialchars($row['indicador_nombre']); ?></td>
                            <td><?php echo $row['periodo']; ?></td>
                            <td><strong><?php echo $row['valor_real']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['registrado_por_nombre']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?></td>
                            <td><?php echo htmlspecialchars($row['observaciones']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
