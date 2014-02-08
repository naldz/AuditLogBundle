<?php

namespace Naldz\Bundle\AuditLogBundle\LogAuditor;

use Naldz\Bundle\AuditLogBundle\LogAuditor\AuditLog;

class LogWriter
{
    private $host;
    private $user;
    private $password;
    private $database;
    
    private $connection;
    private $pdoClass;
    
    
    public function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->pdoClass = '\PDO';
    }
    
    public function setPdoClass($pdoClass)
    {
        $this->pdoClass = $pdoClass;
    }
    
    public function getConnection()
    {
        if (is_null($this->connection)) {
            $connString = sprintf('mysql:host=%s;dbname=%s', $this->host, $this->database);
            $this->connection = new $this->pdoClass($connString, $this->user, $this->password);
        }

        return $this->connection;
    }
    
    public function write(AuditLog $auditLog, $con = null)
    {
        if (is_null($con)) {
            $con = $this->getConnection();
        }

        $stmt = $con->prepare("
            INSERT INTO audit_log (`user_id`, `ip_address`, `event_type`, `message`, `with_details`, `detail_summary`, `summary_version_number`) 
            VALUES (:userId, :ipAddress, :eventType, :message, :withDetails, :detailSummary, :summaryVersionNumber);
        ");
        
        $stmt->execute(array(
            ':userId'       => $auditLog->getUserId(),
            ':ipAddress'    => $auditLog->getIpAddress(),
            ':eventType'    => $auditLog->getEventType(),
            ':message'      => $auditLog->getMessage(),
            ':withDetails'  => $auditLog->getWithDetails(),
            ':detailSummary'=> $auditLog->getDetailSummary(),
            ':summaryVersionNumber' => 0
        ));
    }
}