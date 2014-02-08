<?php

namespace Naldz\Bundle\AuditLogBundle\Event\Processor;

interface EventProcessorInterface
{
    public function process($event, $userInfoProvider, $ipAddress, $eventConfig, $log = null);
}