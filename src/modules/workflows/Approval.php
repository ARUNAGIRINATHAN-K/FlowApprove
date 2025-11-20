<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Database.php';
require_once __DIR__ . '/../requests/Request.php';
require_once __DIR__ . '/../auth/User.php';
require_once __DIR__ . '/../audit/AuditLog.php';

class Approval
{
    private $db;
    private $auditLog;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->auditLog = new AuditLog();
    }

    public function getPendingApprovalsForApprover($approverId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                aa.request_id, 
                aa.level, 
                r.request_title, 
                r.request_type, 
                r.created_at, 
                u.name as user_name
            FROM approval_actions aa
            JOIN requests r ON aa.request_id = r.id
            JOIN users u ON r.user_id = u.id
            WHERE aa.approver_id = ? AND aa.action = 'pending'
            ORDER BY r.created_at ASC"
        );
        $stmt->execute([$approverId]);
        return $stmt->fetchAll();
    }

    public function createApprovalLevel($requestType, $level, $approverRole, $approverUserId)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO approval_levels (request_type, level, approver_role, approver_user_id) 
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$requestType, $level, $approverRole, $approverUserId]);
    }

    public function getAllApprovalLevels()
    {
        $stmt = $this->db->prepare("SELECT * FROM approval_levels ORDER BY request_type, level ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getApprovalLevels($requestType)
    {
        $stmt = $this->db->prepare("SELECT * FROM approval_levels WHERE request_type = ? ORDER BY level ASC");
        $stmt->execute([$requestType]);
        return $stmt->fetchAll();
    }

    public function startWorkflow($requestId, $requestType)
    {
        $levels = $this->getApprovalLevels($requestType);
        if (!empty($levels)) {
            $firstLevel = $levels[0];
            // Find the approver for the first level
            $approverId = $this->getApproverForLevel($firstLevel);
            if ($approverId) {
                $this->createApprovalAction($requestId, $approverId, $firstLevel['level'], 'pending');
            }
        }
    }

    public function createApprovalAction($requestId, $approverId, $level, $action, $comment = '')
    {
        $stmt = $this->db->prepare(
            "INSERT INTO approval_actions (request_id, approver_id, level, action, comment) 
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$requestId, $approverId, $level, $action, $comment]);
    }

    public function approveRequest($requestId, $approverId)
    {
        $request_handler = new \Request();
        $request = $request_handler->getRequestById($requestId);
        $currentLevel = $request['current_level'];

        // Update the approval action
        $stmt = $this->db->prepare("UPDATE approval_actions SET action = 'approved', approver_id = ? WHERE request_id = ? AND level = ? AND action = 'pending'");
        $stmt->execute([$approverId, $requestId, $currentLevel]);

        // Check for next level
        $levels = $this->getApprovalLevels($request['request_type']);
        $nextLevel = null;
        foreach ($levels as $index => $level) {
            if ($level['level'] == $currentLevel && isset($levels[$index + 1])) {
                $nextLevel = $levels[$index + 1];
                break;
            }
        }

        if ($nextLevel) {
            // Move to next level
            $request_handler->updateRequestLevel($requestId, $nextLevel['level']);
            $nextApproverId = $this->getApproverForLevel($nextLevel);
            if ($nextApproverId) {
                $this->createApprovalAction($requestId, $nextApproverId, $nextLevel['level'], 'pending');
            }
        } else {
            // Final approval
            $request_handler->updateRequestStatus($requestId, 'approved');
            $this->auditLog->logAction($approverId, 'approve', $requestId);
        }
    }

    public function rejectRequest($requestId, $approverId, $comment)
    {
        $request_handler = new \Request();
        $request = $request_handler->getRequestById($requestId);
        $currentLevel = $request['current_level'];

        // Update the approval action
        $stmt = $this->db->prepare("UPDATE approval_actions SET action = 'rejected', approver_id = ?, comment = ? WHERE request_id = ? AND level = ? AND action = 'pending'");
        $stmt->execute([$approverId, $comment, $requestId, $currentLevel]);

        // Update request status
        $request_handler->updateRequestStatus($requestId, 'rejected');
        $this->auditLog->logAction($approverId, 'reject', $requestId);
    }

    public function isCurrentUserApprover($requestId, $userId)
    {
        $request_handler = new \Request();
        $request = $request_handler->getRequestById($requestId);
        if (!$request) {
            return false;
        }

        $currentLevel = $request['current_level'];
        $requestType = $request['request_type'];

        $stmt = $this->db->prepare("SELECT * FROM approval_levels WHERE request_type = ? AND level = ?");
        $stmt->execute([$requestType, $currentLevel]);
        $approvalLevel = $stmt->fetch();

        if (!$approvalLevel) {
            return false;
        }

        if (!empty($approvalLevel['approver_user_id'])) {
            return $approvalLevel['approver_user_id'] == $userId;
        }

        $user_handler = new \User();
        $user = $user_handler->getUserById($userId); // This method doesn't exist yet
        if ($user) {
            return $user['role'] === $approvalLevel['approver_role'];
        }

        return false;
    }

    private function getApproverForLevel($level)
    {
        if (!empty($level['approver_user_id'])) {
            return $level['approver_user_id'];
        }

        // If no specific user is assigned, find a user with the specified role.
        // This is a simplified logic. In a real application, you might have more complex rules.
        $stmt = $this->db->prepare("SELECT id FROM users WHERE role = ? LIMIT 1");
        $stmt->execute([$level['approver_role']]);
        $user = $stmt->fetch();
        return $user ? $user['id'] : null;
    }
}
