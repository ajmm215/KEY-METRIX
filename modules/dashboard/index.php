<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarAutenticacion();
require_once __DIR__ . '/../../config/database.php';

$conn = conectarDB();
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM indicadores WHERE estado='activo'");
$stmt->execute();
$totalIndicadores = $stmt->get_result()->fetch_assoc()['total'];

$stmt2 = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE estado='activo'");
$stmt2->execute();
$totalUsuarios = $stmt2->get_result()->fetch_assoc()['total'];

$stmt3 = $conn->prepare("SELECT COUNT(*) as total FROM metas WHERE estado='vigente'");
$stmt3->execute();
$totalMetas = $stmt3->get_result()->fetch_assoc()['total'];

$stmt4 = $conn->prepare("SELECT i.nombre, r.valor_real, m.meta_valor, m.umbral_minimo FROM resultados r JOIN indicadores i ON r.indicador_id = i.id JOIN metas m ON r.meta_id = m.id WHERE r.fecha_registro >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY r.fecha_registro DESC LIMIT 10");
$stmt4->execute();
$ultimosResultados = $stmt4->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-speedometer2"></i> <?php echo APP_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
                    <?php if(tienePermiso('gestionar_usuarios')): ?><li class="nav-item"><a class="nav-link" href="../usuarios/index.php">Usuarios</a></li><?php endif; ?>
                    <?php if(tienePermiso('gestionar_roles')): ?><li class="nav-item"><a class="nav-link" href="../roles/index.php">Roles</a></li><?php endif; ?>
                    <?php if(tienePermiso('gestionar_indicadores')): ?><li class="nav-item"><a class="nav-link" href="../indicadores/index.php">Indicadores</a></li><?php endif; ?>
                    <?php if(tienePermiso('gestionar_metas')): ?><li class="nav-item"><a class="nav-link" href="../metas/index.php">Metas</a></li><?php endif; ?>
                    <?php if(tienePermiso('registrar_resultados')): ?><li class="nav-item"><a class="nav-link" href="../resultados/index.php">Resultados</a></li><?php endif; ?>
                    <?php if(tienePermiso('ver_reportes')): ?><li class="nav-item"><a class="nav-link" href="../reportes/index.php">Reportes</a></li><?php endif; ?>
                    <?php if(tienePermiso('ver_auditoria')): ?><li class="nav-item"><a class="nav-link" href="../auditoria/index.php">Auditoría</a></li><?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nombre_completo']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <h2 class="mb-4">Dashboard General</h2>
        <?php if(isset($_GET['error']) && $_GET['error'] == 'sin_permiso'): ?>
            <div class="alert alert-warning">No tiene permisos para acceder a esa sección.</div>
        <?php endif; ?>
        
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Indicadores Activos</h6>
                                <h2><?php echo $totalIndicadores; ?></h2>
                            </div>
                            <i class="bi bi-bar-chart-line fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Usuarios Activos</h6>
                                <h2><?php echo $totalUsuarios; ?></h2>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Metas Vigentes</h6>
                                <h2><?php echo $totalMetas; ?></h2>
                            </div>
                            <i class="bi bi-trophy fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Sistema</h6>
                                <h2><i class="bi bi-check-circle"></i></h2>
                            </div>
                            <i class="bi bi-shield-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Últimos Resultados Registrados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>Valor Real</th>
                                <th>Meta</th>
                                <th>Umbral Mínimo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $ultimosResultados->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo $row['valor_real']; ?></td>
                                    <td><?php echo $row['meta_valor']; ?></td>
                                    <td><?php echo $row['umbral_minimo']; ?></td>
                                    <td>
                                        <?php if($row['valor_real'] >= $row['meta_valor']): ?>
                                            <span class="badge bg-success">Cumplido</span>
                                        <?php elseif($row['valor_real'] >= $row['umbral_minimo']): ?>
                                            <span class="badge bg-warning">En Riesgo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">No Cumplido</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
