<?php

namespace Naldz\Bundle\AuditLogBundle\Event\Listener;

//use Naldz\Bundle\AuditLogBundle\LogAuditor\LogAuditor;

class EventListener
{
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function listen($event)
    {
        $eventName = $event->getName();
        $allEventConfig = $this->container->getParameter('audit_log.events');

        if (isset($allEventConfig[$eventName])) {
            $eventConfig = $allEventConfig[$eventName];

            //get the logged in user

            //$securityContext = $container->get('security.context');
            //$token = $securityContext->getToken();
            //$user = $token->getUser();

            //get the event processor
            $eventProcessorServiceName = $eventConfig['event_processor_service'];
            $eventProcessor = $this->container->get($eventProcessorServiceName);
            
            //get the user provider
            $userInfoProviderServiceName = $eventConfig['user_info_provider_service'];
            $userInfoProvider = $this->container->get($userInfoProviderServiceName);
            
            $ipAddress = $this->container->get('request')->getClientIp();
            
            $auditLog = $eventProcessor->process($event, $userInfoProvider, $ipAddress, $eventConfig);

            // $dbHost = $this->container->getParameter('audit_log.connection_host');
// $dbUser = $this->container->getParameter('audit_log.connection_user');
//             $dbPassword = $this->container->getParameter('audit_log.connection_password');
//             $dbName = $this->container->getParameter('audit_log.connection_database');
            //$versionNum = $container->getParameter('audit_log.summary_version_number');

            //$logAuditor = new LogAuditor($dbHost, $dbUser, $dbPassword, $dbName);
            $logWriter = $this->container->get('audit_log.writer');
            $logWriter->write($auditLog);

        }
    }
}