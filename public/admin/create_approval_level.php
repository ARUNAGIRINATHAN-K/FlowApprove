<?php
session_start();
require_once '../../src/config.php';
require_once '../../src/modules/auth/User.php';
require_once '../../src/modules/workflows/Approval.php';

$user = new User();
if (!$user->isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $approval = new Approval();
    $requestType = $_POST['request_type'];
    $level = $_POST['level'];
    $approverRole = $_POST['approver_role'];
    $approverUserId = !empty($_POST['approver_user_id']) ? $_POST['approver_user_id'] : null;

    $approval->createApprovalLevel($requestType, $level, $approverRole, $approverUserId); // This method doesn't exist yet

    header('Location: approval_levels.php');
    exit;
}

include_once '../../templates/admin/header.php';
?>

<h2>Create Approval Level</h2>

<form action="create_approval_level.php" method="post">
    <div class="mb-3">
        <label for="request_type" class="form-label">Request Type</label>
        <input type="text" class="form-control" id="request_type" name="request_type" required>
    </div>
    <div class="mb-3">
        <label for="level" class="form-label">Level</label>
        <input type="number" class="form-control" id="level" name="level" required>
    </div>
    <div class="mb-3">
        <label for="approver_role" class="form-label">Approver Role</label>
        <select class="form-select" id="approver_role" name="approver_role">
            <option value="manager">Manager</option>
            <option value="finance">Finance</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="approver_user_id" class="form-label">Approver User ID (Optional)</label>
        <input type="number" class="form-control" id="approver_user_id" name="approver_user_id">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>

<?php include_once '../../templates/admin/footer.php'; ?>
