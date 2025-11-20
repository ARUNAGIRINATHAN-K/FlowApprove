<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/requests/Request.php';
require_once '../src/modules/auth/User.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$request = new Request();
$requests = $request->getRequestsByUserId($_SESSION['user_id']);

include_once '../templates/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Requests</h2>
    <a href="create_request.php" class="btn btn-primary">Create Request</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Level</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $req) : ?>
            <tr>
                <td><?php echo $req['id']; ?></td>
                <td><?php echo $req['request_title']; ?></td>
                <td><?php echo $req['request_type']; ?></td>
                <td><?php echo $req['status']; ?></td>
                <td><?php echo $req['current_level']; ?></td>
                <td><?php echo $req['created_at']; ?></td>
                <td>
                    <a href="view_request.php?id=<?php echo $req['id']; ?>" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include_once '../templates/footer.php'; ?>
