<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/requests/Request.php';
require_once '../src/modules/auth/User.php';
require_once '../src/modules/workflows/Approval.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$request_id = $_GET['id'] ?? 0;

$request_handler = new Request();
$request = $request_handler->getRequestById($request_id);
$attachments = $request_handler->getAttachmentsByRequestId($request_id);

$approval_handler = new Approval();
$is_approver = $approval_handler->isCurrentUserApprover($request_id, $_SESSION['user_id']); // This method doesn't exist yet

include_once '../templates/header.php';
?>

<h2>Request Details</h2>

<?php if ($request) : ?>
    <div class="card">
        <div class="card-header">
            Request #<?php echo $request['id']; ?>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $request['request_title']; ?></h5>
            <p class="card-text"><strong>Type:</strong> <?php echo $request['request_type']; ?></p>
            <p class="card-text"><strong>Status:</strong> <?php echo $request['status']; ?></p>
            <p class="card-text"><strong>Description:</strong> <?php echo $request['request_description']; ?></p>
            <p class="card-text"><strong>Created At:</strong> <?php echo $request['created_at']; ?></p>
        </div>
    </div>

    <?php if ($attachments) : ?>
        <h4 class="mt-4">Attachments</h4>
        <ul>
            <?php foreach ($attachments as $attachment) : ?>
                <li><a href="<?php echo $attachment['file_path']; ?>" target="_blank"><?php echo basename($attachment['file_path']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($is_approver && $request['status'] === 'pending') : ?>
        <div class="mt-4">
            <h4>Actions</h4>
            <a href="approve_request.php?id=<?php echo $request_id; ?>" class="btn btn-success">Approve</a>
            <a href="reject_request.php?id=<?php echo $request_id; ?>" class="btn btn-danger">Reject</a>
        </div>
    <?php endif; ?>

<?php else : ?>
    <div class="alert alert-warning">Request not found.</div>
<?php endif; ?>

<div class="mt-3">
    <a href="requests.php" class="btn btn-secondary">Back to Requests</a>
</div>


<?php include_once '../templates/footer.php'; ?>
