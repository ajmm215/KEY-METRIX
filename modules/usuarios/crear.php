<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarPermiso('gestionar_usuarios');
require_once __DIR__ . '/../../config/database.php';

$conn = conectarDB();
$roles = $conn->query("SELECT * FROM roles WHERE estado='activo'");
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitizar($_POST['nombre_completo']);
    $usuario = sanitizar($_POST['usuario']);
    $email = sanitizar($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol_id = $_POST['rol_id'];
    
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, usuario, email, password, rol_id) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssssi", $nombre, $usuario, $email, $password, $rol_id);
    if ($stmt->execute()) {
        $stmt2 = $conn->prepare("INSERT INTO auditoria (usuario_id, accion, ip, fecha) VALUES (?, ?, ?, NOW())");
        $accion = "Creó usuario: " . $usuario;
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt2->bind_param("iss", $_SESSION['usuario_id'], $accion, $ip);
        $stmt2->execute();
        header('Location: index.php?msg=Usuario creado correctamente');
        exit();
    } else {
        $msg = 'Error al crear usuario';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard/index.php"><?php echo APP_NAME; ?></a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Usuario</h5>
                    </div>
                    <div class="card-body">
                        <?php if($msg): ?><div class="alert alert-danger"><?php echo $msg; ?></div><?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" name="nombre_completo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" name="usuario" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rol</label>
                                <select name="rol_id" class="form-select" required>
                                    <?php while($r = $roles->fetch_assoc()): ?>
                                        <option value="<?php echo $r['id']; ?>"><?php echo $r['nombre']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
