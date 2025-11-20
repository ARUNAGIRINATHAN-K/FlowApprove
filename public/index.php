<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/auth/User.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'] ?? 'employee'; // Default to employee if role is not set

switch ($role) {
    case 'admin':
        header('Location: dashboard_admin.php');
        break;
    case 'manager':
        header('Location: dashboard_manager.php');
        break;
    case 'employee':
    default:
        header('Location: dashboard_employee.php');
        break;
}
exit;
?>
