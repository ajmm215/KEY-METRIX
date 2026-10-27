<?php
require_once dirname(dirname(__DIR__)) . '/includes/funciones.php';
verificarPermiso('gestionar_usuarios');
require_once dirname(dirname(__DIR__)) . '/config/database.php';

$conn = conectarDB();
$msg = '';
if (isset($_GET['msg'])) $msg = $_GET['msg'];

$usuarios = $conn->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON u.rol_id = r.id ORDER BY u.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard/index.php"><i class="bi bi-speedometer2"></i> <?php echo APP_NAME; ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="../dashboard/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="../roles/index.php">Roles</a></li>
                    <li class="nav-item"><a class="nav-link" href="../indicadores/index.php">Indicadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../metas/index.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link" href="../resultados/index.php">Resultados</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../../auth/logout.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="bi bi-people"></i> Gestión de Usuarios</h2>
            <a href="crear.php" class="btn btn-primary"><i class="bi bi-person-plus"></i> Nuevo Usuario</a>
        </div>
        <?php if($msg): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $usuarios->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><span class="badge bg-info"><?php echo $row['rol_nombre']; ?></span></td>
                            <td><span class="badge bg-<?php echo $row['estado']=='activo'?'success':'danger'; ?>"><?php echo $row['estado']; ?></span></td>
                            <td>
                                <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
