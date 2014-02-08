<?php

namespace Naldz\Bundle\AuditLogBundle\Tests\Unit\LogAuditor;

use Naldz\Bundle\AuditLogBundle\LogAuditor\AuditLog;

class AuditLogTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSettersAndGetters()
    {
        $auditLog = new AuditLog();
        $auditLog->setUserId(123);
        $this->assertEquals(123, $auditLog->getUserId());
        
        $auditLog = new AuditLog();
        $auditLog->setIpAddress('192.168.1.1');
        $this->assertEquals('192.168.1.1', $auditLog->getIpAddress());
        
        $auditLog = new AuditLog();
        $auditLog->setEventType('ADD_USER');
        $this->assertEquals('ADD_USER', $auditLog->getEventType());
        
        $auditLog = new AuditLog();
        $auditLog->setMessage('Test Message');
        $this->assertEquals('Test Message', $auditLog->getMessage());
        
        $auditLog = new AuditLog();
        $auditLog->setWithDetails(TRUE);
        $this->assertEquals(TRUE, $auditLog->getWithDetails());
        
        $auditLog = new AuditLog();
        $auditLog->setDetailSummary('Test detail summary');
        $this->assertEquals('Test detail summary', $auditLog->getDetailSummary());
        
        $auditLog = new AuditLog();
        $auditLog->setInitialSubjectValue('{"json_data":"json_value"}');
        $this->assertEquals('{"json_data":"json_value"}', $auditLog->getInitialSubjectValue());
        
        $auditLog = new AuditLog();
        $auditLog->setInitialSubjectValue('{"json_data":"json_value"}');
        $this->assertEquals('{"json_data":"json_value"}', $auditLog->getInitialSubjectValue());
        
        $auditLog = new AuditLog();
        $auditLog->setFinalSubjectValue('{"json_data":"json_final_value"}');
        $this->assertEquals('{"json_data":"json_final_value"}', $auditLog->getFinalSubjectValue());
    }
}