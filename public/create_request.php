<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/auth/User.php';
include_once '../templates/header.php';

$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>

<h2>Create Request</h2>

<form action="submit_request.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="request_type" class="form-label">Request Type</label>
        <select class="form-select" id="request_type" name="request_type">
            <option value="Leave">Leave</option>
            <option value="Purchase Order">Purchase Order</option>
            <option value="Asset Request">Asset Request</option>
            <option value="IT Support">IT Support</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="request_title" class="form-label">Title</label>
        <input type="text" class="form-control" id="request_title" name="request_title" required>
    </div>
    <div class="mb-3">
        <label for="request_description" class="form-label">Description</label>
        <textarea class="form-control" id="request_description" name="request_description" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="attachment" class="form-label">Attachment (Optional)</label>
        <input class="form-control" type="file" id="attachment" name="attachment">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php include_once '../templates/footer.php'; ?>
