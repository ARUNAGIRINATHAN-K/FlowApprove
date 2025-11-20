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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $approval_handler = new Approval();
    $approval_handler->rejectRequest($request_id, $approver_id, $comment); // This method doesn't exist yet
    header("Location: view_request.php?id=$request_id");
    exit;
}

include_once '../templates/header.php';
?>

<h2>Reject Request</h2>

<form action="reject_request.php?id=<?php echo $request_id; ?>" method="post">
    <div class="mb-3">
        <label for="comment" class="form-label">Comment</label>
        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-danger">Reject</button>
</form>

<?php include_once '../templates/footer.php'; ?>
