<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/requests/Request.php';
require_once '../src/modules/auth/User.php';
require_once '../src/modules/workflows/Approval.php'; // I will create this class later

$user = new User();
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = new Request();
    $requestType = $_POST['request_type'];
    $requestTitle = $_POST['request_title'];
    $requestDescription = $_POST['request_description'];
    $userId = $_SESSION['user_id'];

    $requestId = $request->createRequest($userId, $requestType, $requestTitle, $requestDescription);

    if ($requestId) {
        // Handle file upload
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $targetDir = "uploads/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = basename($_FILES["attachment"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            
            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
                $request->addAttachment($requestId, $targetFilePath);
            }
        }

        // Trigger workflow
        $approval = new Approval();
        $approval->startWorkflow($requestId, $requestType);

        header('Location: requests.php');
        exit;
    } else {
        // Handle error
        echo "Error creating request.";
    }
} else {
    header('Location: create_request.php');
    exit;
}
