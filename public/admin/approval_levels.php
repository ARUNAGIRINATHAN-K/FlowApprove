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

$approval = new Approval();
// This is not efficient, but for simplicity, I will fetch all levels and group them by request type.
$allLevels = $approval->getAllApprovalLevels(); // This method doesn't exist yet.

$levelsByRequestType = [];
foreach ($allLevels as $level) {
    $levelsByRequestType[$level['request_type']][] = $level;
}

include_once '../../templates/admin/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Approval Levels</h2>
    <a href="create_approval_level.php" class="btn btn-primary">Create Approval Level</a>
</div>

<?php foreach ($levelsByRequestType as $requestType => $levels) : ?>
    <h4 class="mt-4"><?php echo $requestType; ?></h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Level</th>
                <th>Approver Role</th>
                <th>Approver User ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($levels as $level) : ?>
                <tr>
                    <td><?php echo $level['level']; ?></td>
                    <td><?php echo $level['approver_role']; ?></td>
                    <td><?php echo $level['approver_user_id']; ?></td>
                    <td>
                        <a href="edit_approval_level.php?id=<?php echo $level['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>


<?php include_once '../../templates/admin/footer.php'; ?>
