<?php

namespace Naldz\Bundle\AuditLogBundle\Tests\Unit\LogAuditor;

use Naldz\Bundle\AuditLogBundle\LogAuditor\LogWriter;

class LogWriterTest extends \PHPUnit_Framework_TestCase
{

    protected $logAuditor;
    
    protected function setUp()
    {
        $this->logWriter = new LogWriter('host','user','password','database');
    }

    public function testGetConnection()
    {
        $this->logWriter->setPdoClass('Naldz\Bundle\AuditLogBundle\TestHelper\Stub\PDOStub');
        $conn = $this->logWriter->getConnection();
        
        $this->assertInstanceOf('Naldz\Bundle\AuditLogBundle\TestHelper\Stub\PDOStub', $conn);
        $this->assertEquals('mysql:host=host;dbname=database', $conn->getConnectionString());
        $this->assertEquals('user', $conn->getUser());
        $this->assertEquals('password', $conn->getPassword());
    }
    
    public function testAuditing()
    {
        $userId = 12345;
        $ipAddress = '192.168.1.121';
        $eventType = 'ADD_PATIENT';
        $message = 'Test Message';
        $withDetails = true;
        $detailSummary = 'Detail Summary';
        $summaryVersionNumber = 1;
        
        $stmtMock = $this->getMockBuilder('Naldz\Bundle\AuditLogBundle\TestHelper\Stub\PDOStatementStub')
            ->disableOriginalConstructor()
            ->getMock();
            
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(array(
                ':userId'       => $userId,
                ':ipAddress'    => $ipAddress,
                ':eventType'    => $eventType,
                ':message'      => $message,
                ':withDetails'  => $withDetails,
                ':detailSummary'=> $detailSummary,
                ':summaryVersionNumber' => 0
            ));

        $pdoMock =$this->getMockBuilder('Naldz\\Bundle\\AuditLogBundle\\TestHelper\\Stub\\PDOStub')
            ->disableOriginalConstructor()
            ->getMock();
        
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($stmtMock));
        
        
        $auditLogMock = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\LogAuditor\\AuditLog');
        $auditLogMock->expects($this->once())
            ->method('getUserId')
            ->will($this->returnValue($userId));
        
        $auditLogMock->expects($this->once())
            ->method('getIpAddress')
            ->will($this->returnValue($ipAddress));
        
        $auditLogMock->expects($this->once())
            ->method('getEventType')
            ->will($this->returnValue($eventType));

        $auditLogMock->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue($message));
        
        $auditLogMock->expects($this->once())
            ->method('getWithDetails')
            ->will($this->returnValue($withDetails));
        
        $auditLogMock->expects($this->once())
            ->method('getDetailSummary')
            ->will($this->returnValue($detailSummary));

        $this->logWriter->write($auditLogMock, $pdoMock);

    }
}