<?php

namespace Naldz\Bundle\AuditLogBundle\Tests\Unit\Event\Listener;

use Naldz\Bundle\AuditLogBundle\Event\Listener\EventListener;

class EventListenerTest extends \PHPUnit_Framework_TestCase
{
    
    public function testListening()
    {
        //mock the event
        $event = $this->getMock('Symfony\\Component\\EventDispatcher\\GenericEvent');
        $event->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('action.one'));
        
        $userInfoProvider = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\User\\UserProviderInterface');
        // $userInfoProvider->expects($this->once())
//             ->method('getId')
//             ->will($this->returnValue(12321));
        // $userInfoProvider->expects($this->once())
        //     ->method('getName')
        //     ->will($this->returnValue('Juan Delacruz'));
        
        $eventConfig = array(
            'message' => 'Action one message',
            'event_processor_service' => 'TestEventProcessor',
            'user_info_provider_service' => 'TestUserInformationProvider'
        );
        
        $auditLog = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\LogAuditor\\AuditLog');
        
        $eventProcessor = $this->getMock('Naldz\\Bundle\\AuditLogBundle\\Event\\Processor\\EventProcessorInterface');
        $eventProcessor->expects($this->once())
            ->method('process')
            ->with($event, $userInfoProvider, '111.111.111.111', $eventConfig)
            ->will($this->returnValue($auditLog));
        
        $logWriter = $this->getMockBuilder('Naldz\\Bundle\\AuditLogBundle\\LogAuditor\\LogWriter')
            ->disableOriginalConstructor()
            ->getMock();
        
        $logWriter->expects($this->once())
            ->method('write')
            ->with($auditLog);
        
        $container = $this->getMock('Symfony\\Component\\DependencyInjection\\ContainerInterface');
            
        $container->expects($this->at(0))
            ->method('getParameter')
            ->with('audit_log.events')
            ->will($this->returnValue(array(
                'action.one' => $eventConfig
            )));
        $container->expects($this->at(1))
            ->method('get')
            ->with('TestEventProcessor')
            ->will($this->returnValue($eventProcessor));
        $container->expects($this->at(2))
            ->method('get')
            ->with('TestUserInformationProvider')
            ->will($this->returnValue($userInfoProvider));
        
        $request = $this->getMock('Symfony\\Component\\HttpFoundation\\Request');
        $request->expects($this->once())
            ->method('getClientIp')
            ->will($this->returnValue('111.111.111.111'));
        
        $container->expects($this->at(3))
            ->method('get')
            ->with('request')
            ->will($this->returnValue($request));
        
        $container->expects($this->at(4))
            ->method('get')
            ->with('audit_log.writer')
            ->will($this->returnValue($logWriter));

        $eventListener = new EventListener($container);
        $eventListener->listen($event);
    }
    
}