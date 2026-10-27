<?php
session_start();
require_once __DIR__ . '/../config/database.php';

function estaAutenticado() {
    return isset($_SESSION['usuario_id']);
}

function verificarAutenticacion() {
    if (!estaAutenticado()) {
        header('Location: ' . APP_URL . 'auth/login.php');
        exit();
    }
}

function tienePermiso($permiso) {
    if (!isset($_SESSION['permisos'])) return false;
    return in_array($permiso, $_SESSION['permisos']);
}

function verificarPermiso($permiso) {
    verificarAutenticacion();
    if (!tienePermiso($permiso)) {
        header('Location: ' . APP_URL . 'modules/dashboard/index.php?error=sin_permiso');
        exit();
    }
}

function sanitizar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
