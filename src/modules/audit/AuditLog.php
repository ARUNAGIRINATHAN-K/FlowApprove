<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Database.php';

class AuditLog
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function logAction($actionBy, $actionType, $requestId)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO audit_logs (action_by, action_type, request_id) 
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$actionBy, $actionType, $requestId]);
    }
}
