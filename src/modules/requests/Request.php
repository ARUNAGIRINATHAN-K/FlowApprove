<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Database.php';
require_once __DIR__ . '/../audit/AuditLog.php';

class Request
{
    private $db;
    private $auditLog;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->auditLog = new AuditLog();
    }

    public function createRequest($userId, $requestType, $requestTitle, $requestDescription)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO requests (user_id, request_type, request_title, request_description, current_level) 
             VALUES (?, ?, ?, ?, 1)"
        );
        if ($stmt->execute([$userId, $requestType, $requestTitle, $requestDescription])) {
            $requestId = $this->db->lastInsertId();
            $this->auditLog->logAction($userId, 'submit', $requestId);
            return $requestId;
        }
        return false;
    }

    public function addAttachment($requestId, $filePath)
    {
        $stmt = $this->db->prepare("INSERT INTO request_attachments (request_id, file_path) VALUES (?, ?)");
        return $stmt->execute([$requestId, $filePath]);
    }

    public function getRequestsByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAttachmentsByRequestId($requestId)
    {
        $stmt = $this->db->prepare("SELECT * FROM request_attachments WHERE request_id = ?");
        $stmt->execute([$requestId]);
        return $stmt->fetchAll();
    }

    public function getRequestById($requestId)
    {
        $stmt = $this->db->prepare("SELECT * FROM requests WHERE id = ?");
        $stmt->execute([$requestId]);
        return $stmt->fetch();
    }

    public function updateRequestLevel($requestId, $level)
    {
        $stmt = $this->db->prepare("UPDATE requests SET current_level = ? WHERE id = ?");
        return $stmt->execute([$level, $requestId]);
    }

    public function updateRequestStatus($requestId, $status)
    {
        $stmt = $this->db->prepare("UPDATE requests SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $requestId]);
    }

    public function getAllRequests()
    {
        $stmt = $this->db->prepare("SELECT r.*, u.name as user_name FROM requests r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
