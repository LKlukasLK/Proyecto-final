<?php
// admin/auth_check.php
session_start();

// Si no está logueado O el rol no es admin, lo echamos al login
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin@tienda.com') {
    header('Location: ../index.php?ver=login');
    exit();
}
?>