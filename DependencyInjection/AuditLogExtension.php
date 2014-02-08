<?php

namespace Naldz\Bundle\AuditLogBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AuditLogExtension extends Extension
{
    /**
     * Loads the configuration.
     *
     * @param array            $configs   An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();

        $configuration = $this->getConfiguration($configs, $container);
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('audit_log.connection_host', $config['connection']['host']);
        $container->setParameter('audit_log.connection_user', $config['connection']['username']);
        $container->setParameter('audit_log.connection_password', $config['connection']['password']);
        $container->setParameter('audit_log.connection_database', $config['connection']['database']);

        //register event listeners
        $eventConfig = array();
        $eventDefinition = new Definition('Naldz\Bundle\AuditLogBundle\Event\Listener\EventListener', array(new Reference('service_container')));
        foreach ($config['events'] as $eventName => $eventParams) {
            $eventDefinition->addTag('kernel.event_listener', array('method' => 'listen', 'event' => $eventName));
            if (isset($eventParams['message']) && strlen($eventParams['message'])) {
                $eventConfig[$eventName] = array(
                    'message' => $eventParams['message'],
                    'event_processor_service' => isset($eventParams['event_processor_service']) 
                        ? $eventParams['event_processor_service'] : $config['event_processor_service'],
                    'user_info_provider_service' => $config['user_info_provider_service'],
                    'with_details' => $eventParams['with_details']
                );
            }
        }
        
        $container->setParameter('audit_log.events', $eventConfig);
        $container->setDefinition('audit_log.event_listener', $eventDefinition);

    }
}