<?php
session_start();
require_once '../../src/config.php';
require_once '../../src/modules/auth/User.php';

$user = new User();
if (!$user->isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

include_once '../../templates/admin/header.php';
?>

<h2>Edit Approval Level</h2>

<p>This feature is not yet implemented.</p>

<?php include_once '../../templates/admin/footer.php'; ?>
