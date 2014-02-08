<?php

namespace Naldz\Bundle\AuditLogBundle\Tests\Unit\DependencyInjection;

use Naldz\Bundle\AuditLogBundle\DependencyInjection\AuditLogExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class AuditLogExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $kernel;
    private $container;
    
    private $dbHost = 'database_host';
    private $dbUser = 'database_user';
    private $dbPass = 'database_pass';
    private $dbName = 'database_name';
    
    protected function setUp()
    {
        $this->kernel = $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');
        $this->container = new ContainerBuilder();
    }

    public function testCompiledConnectionConfig()
    {
        $yaml = new Parser();
        $rawConfig = $yaml->parse(sprintf('
audit_log:
    connection:
        host: "%s"
        username: "%s"
        password: "%s"
        database: "%s"
    event_processor_service: Naldz\Bundle\AuditLogBundle\Event\Processor\PropelEventProcessor
    user_info_provider_service: audit_log.userinfo.provider
    events: ~
', $this->dbHost, $this->dbUser, $this->dbPass, $this->dbName));

        $extension = new AuditLogExtension();
        $extension->load($rawConfig, $this->container);
        $this->container->compile();
        
        $this->assertEquals($this->container->getParameter('audit_log.connection_host'), $this->dbHost);
        $this->assertEquals($this->container->getParameter('audit_log.connection_user'), $this->dbUser);
        $this->assertEquals($this->container->getParameter('audit_log.connection_password'), $this->dbPass);
        $this->assertEquals($this->container->getParameter('audit_log.connection_database'), $this->dbName);
    }
    
    public function testCompiledEventListenerRegistration()
    {
        $yaml = new Parser();
        $rawConfig = $yaml->parse(sprintf('
audit_log:
    connection:
        host: "%s"
        username: "%s"
        password: "%s"
        database: "%s"
    event_processor_service: Naldz\Bundle\AuditLogBundle\Event\Processor\PropelEventProcessor
    user_info_provider_service: audit_log.userinfo.provider
    events: 
        patient.archive:
            message: "Patient has been archived."
        patient.add:
            message: "Patient has been added."
', $this->dbHost, $this->dbUser, $this->dbPass, $this->dbName));

        $extension = new AuditLogExtension();
        $extension->load($rawConfig, $this->container);
        $this->container->compile();
        
        $expectedTaggedServices = array(
            'audit_log.event_listener' => array(
                array(
                    'method' => 'listen',
                    'event'  => 'patient.archive'
                ),
                array(
                    'method' => 'listen',
                    'event'  => 'patient.add'
                )
            )
        );
        $actualTaggedServices = $this->container->findTaggedServiceIds('kernel.event_listener');
        $this->assertEquals($expectedTaggedServices, $actualTaggedServices);
    }

    public function testCompiledEventConfigParameter()
    {
        $yaml = new Parser();
        $rawConfig = $yaml->parse(sprintf('
audit_log:
    connection:
        host: "%s"
        username: "%s"
        password: "%s"
        database: "%s"
    event_processor_service: audit_log.event_processor
    user_info_provider_service: audit_log.userinfo.provider
    events: 
        patient.archive:
            message: "Patient has been archived."
        patient.add:
            message: "Patient has been added."
            event_processor_service: audit_log.event_processor_new
            with_details: true
            
', $this->dbHost, $this->dbUser, $this->dbPass, $this->dbName));

        $extension = new AuditLogExtension();
        $extension->load($rawConfig, $this->container);
        $this->container->compile();

        $expectedEventConfig = array(
            'patient.archive' => array(
                'message' => 'Patient has been archived.',
                'user_info_provider_service' => 'audit_log.userinfo.provider',
                'event_processor_service' => 'audit_log.event_processor',
                'with_details' => false
            ),
            'patient.add' => array(
                'message' => 'Patient has been added.',
                'user_info_provider_service' => 'audit_log.userinfo.provider',
                'event_processor_service' => 'audit_log.event_processor_new',
                'with_details' => true
            )
        );
        
        $actualEventConfig = $this->container->getParameter('audit_log.events');
        
        $this->assertEquals($expectedEventConfig, $actualEventConfig);
    }

}
