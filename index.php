<?php
require_once __DIR__ . '/../../includes/funciones.php';
verificarAutenticacion();
if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . APP_URL . 'modules/dashboard/index.php');
    exit();
}
header('Location: ' . APP_URL . 'auth/login.php');
exit();
