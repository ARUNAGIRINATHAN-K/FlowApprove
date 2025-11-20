<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/auth/User.php';
require_once '../src/modules/workflows/Approval.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$request_id = $_GET['id'] ?? 0;
$approver_id = $_SESSION['user_id'];

$approval_handler = new Approval();
$approval_handler->approveRequest($request_id, $approver_id); // This method doesn't exist yet

header("Location: view_request.php?id=$request_id");
exit;
