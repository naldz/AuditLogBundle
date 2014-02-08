<?php

namespace Naldz\Bundle\AuditLogBundle\Event\Processor;

use Naldz\Bundle\AuditLogBundle\Event\Processor\EventProcessorInterface;
use Naldz\Bundle\AuditLogBundle\LogAuditor\AuditLog;

class PropelEventProcessor implements EventProcessorInterface
{

    public function process($event, $userInfoProvider, $ipAddress, $eventConfig, $log = null)
    {
        if (is_null($log)) {
            $log = new AuditLog();
        }
        
        $log->setUserId($userInfoProvider->getId());
        $log->setIpAddress($ipAddress);
        $log->setEventType($event->getName());
        $log->setMessage($eventConfig['message']);
        $log->setWithDetails($eventConfig['with_details']);

        if ($eventConfig['with_details']) {
            //$log->setDetailSummary();
            $subject = $event->getSubject();
            $log->setInitialSubjectValue($subject->getOriginalFieldValues());
            $log->setFinalSubjectValue($subject->toArray(1));
        }

        return $log;
    }
}