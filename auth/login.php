<?php
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../config/database.php';

if (estaAutenticado()) {
    header('Location: ' . APP_URL . 'modules/dashboard/index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = sanitizar($_POST['usuario']);
    $password = $_POST['password'];
    
    $conn = conectarDB();
    $stmt = $conn->prepare("SELECT id, nombre_completo, usuario, password, rol_id, estado FROM usuarios WHERE usuario = ? AND estado = 'activo'");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre_completo'] = $user['nombre_completo'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol_id'] = $user['rol_id'];
            
            $stmt2 = $conn->prepare("SELECT p.nombre FROM permisos p JOIN rol_permisos rp ON p.id = rp.permiso_id WHERE rp.rol_id = ?");
            $stmt2->bind_param("i", $user['rol_id']);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $_SESSION['permisos'] = [];
            while ($row = $res2->fetch_assoc()) {
                $_SESSION['permisos'][] = $row['nombre'];
            }
            
            $stmt3 = $conn->prepare("INSERT INTO auditoria (usuario_id, accion, ip, fecha) VALUES (?, 'Inicio de sesión', ?, NOW())");
            $ip = $_SERVER['REMOTE_ADDR'];
            $stmt3->bind_param("is", $user['id'], $ip);
            $stmt3->execute();
            
            header('Location: ' . APP_URL . 'modules/dashboard/index.php');
            exit();
        } else {
            $error = 'Contraseña incorrecta';
        }
    } else {
        $error = 'Usuario no encontrado o inactivo';
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { max-width: 400px; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary"><?php echo APP_NAME; ?></h2>
                    <p class="text-muted">Sistema de Gestión de Desempeño por KPIs</p>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </form>
                <div class="text-center mt-3">
                    <small class="text-muted">Usuario por defecto: admin / admin123</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
