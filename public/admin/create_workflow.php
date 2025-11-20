<?php
session_start();
require_once '../../src/config.php';
require_once '../../src/modules/auth/User.php';
require_once '../../src/modules/workflows/Workflow.php';
require_once '../../src/modules/workflows/WorkflowStep.php';

$user = new User();
if (!$user->isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workflow = new Workflow();
    $workflowStep = new WorkflowStep();

    $name = $_POST['name'];
    $requestType = $_POST['request_type'];
    $steps = $_POST['steps'];

    $workflowId = $workflow->createWorkflow($name, $requestType);

    if ($workflowId && !empty($steps)) {
        $workflowStep->createWorkflowSteps($workflowId, $steps);
    }

    header('Location: workflows.php');
    exit;
}

include_once '../../templates/admin/header.php';
?>

<h2>Create Workflow</h2>

<form action="create_workflow.php" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Workflow Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="request_type" class="form-label">Request Type</label>
        <input type="text" class="form-control" id="request_type" name="request_type" required>
    </div>
    <hr>
    <h4>Workflow Steps</h4>
    <div id="steps-container">
        <!-- Steps will be added here dynamically -->
    </div>
    <button type="button" class="btn btn-secondary" id="add-step">Add Step</button>
    <hr>
    <button type="submit" class="btn btn-primary">Create Workflow</button>
</form>

<script>
document.getElementById('add-step').addEventListener('click', function() {
    const stepsContainer = document.getElementById('steps-container');
    const stepIndex = stepsContainer.children.length;
    const stepHtml = `
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Step Number</label>
                <input type="number" class="form-control" name="steps[\${stepIndex}][step_number]" required>
            </div>
            <div class="col">
                <label class="form-label">Approver Role ID</label>
                <input type="number" class="form-control" name="steps[\${stepIndex}][approver_role_id]" required>
            </div>
        </div>
    `;
    stepsContainer.insertAdjacentHTML('beforeend', stepHtml);
});
</script>

<?php include_once '../../templates/admin/footer.php'; ?>
