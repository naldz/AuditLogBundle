<?php

namespace Naldz\Bundle\AuditLogBundle\Tests\Unit\Event\Processor;

use Naldz\Bundle\AuditLogBundle\Event\Processor\PropelEventProcessor;


class PropelEventProcessorTest extends \PHPUnit_Framework_TestCase
{
    
    public function testProcessing()
    {
        $logUserId = 12345;
        $ipAddress = '111.111.111.111';
        
        $initialFieldValues = array(
            'field_1' => 'Field One Value',
            'field_2' => 'Field Two Value' 
        );
        
        $finalFieldValues = array(            
            'field_1' => 'Modified Field One Value',
            'field_2' => 'Field Two Value'
        );
        
        $subjectMock = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\TestHelper\\Stub\\PropelObjectStub');
        $subjectMock->expects($this->once())
            ->method('getOriginalFieldValues')
            ->will($this->returnValue($initialFieldValues));
        
        $subjectMock->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($finalFieldValues));

        $event = $this->getMock('Symfony\\Component\\EventDispatcher\\GenericEvent');
        $event->expects($this->once())
            ->method('getSubject')
            ->will($this->returnValue($subjectMock));
        $event->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('action.one'));
        
        $user = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\User\\UserInfoProviderInterface');
        $user->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($logUserId));
        
        $eventConfig = array(
            'message' => 'Action one performed',
            'user_info_provider_service' => 'audit_log.userinfo.provider',
            'event_processor_service' => 'audit_log.event_processor',
            'with_details' => true
        );

        $auditLog = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\LogAuditor\\AuditLog');
        $auditLog->expects($this->once())
            ->method('setUserId')
            ->with($logUserId);
        $auditLog->expects($this->once())
            ->method('setIpAddress')
            ->with($ipAddress);
        $auditLog->expects($this->once())
            ->method('setEventType')
            ->with('action.one');
        $auditLog->expects($this->once())
            ->method('setMessage')
            ->with('Action one performed');
        $auditLog->expects($this->once())
            ->method('setWithDetails')
            ->with(true);
        $auditLog->expects($this->once())
            ->method('setInitialSubjectValue')
            ->with($initialFieldValues);
        $auditLog->expects($this->once())
            ->method('setFinalSubjectValue')
            ->with($finalFieldValues);
        
        $propelEventProcessor = new PropelEventProcessor();
        $propelEventProcessor->process($event, $user, $ipAddress, $eventConfig, $auditLog);
    }
    
}
