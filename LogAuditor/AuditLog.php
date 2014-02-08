<?php

namespace Naldz\Bundle\AuditLogBundle\LogAuditor;

class AuditLog
{
    private $userId;
    private $ipAddress;
    private $eventType;
    private $message;
    private $withDetails;
    private $detailSummary;
    private $initialSubjectValue;
    private $finalSubjectValue;

    
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }
    
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    public function setWithDetails($withDetails)
    {
        $this->withDetails = $withDetails;
    }
    
    public function setDetailSummary($detailSummary)
    {
        $this->detailSummary = $detailSummary;
    }
    
    public function setInitialSubjectValue($initialSubjectValue)
    {
        $this->initialSubjectValue = $initialSubjectValue;
    }
    
    public function setFinalSubjectValue($finalSubjectValue)
    {
        $this->finalSubjectValue = $finalSubjectValue;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function getIpAddress()
    {
        return $this->ipAddress;
    }
    
    public function getEventType()
    {
        return $this->eventType;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function getWithDetails()
    {
        return $this->withDetails;
    }
    
    public function getDetailSummary()
    {
        return $this->detailSummary;
    }
    
    public function getInitialSubjectValue()
    {
        return $this->initialSubjectValue;
    }
    
    public function getFinalSubjectValue()
    {
        return $this->finalSubjectValue;
    }
    
}