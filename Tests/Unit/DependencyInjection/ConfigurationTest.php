<?php

namespace Naldz\Bundle\AuditLogBundle\Tests\Unit\DependencyInjection;

use Naldz\Bundle\AuditLogBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testNormalEventConfiguration()
    {
        $yaml = new Parser();
        $rawConfig = $yaml->parse('
audit_log:
    connection:
        host: "localhost"
        username: "root"
        password: "password"
        database: "database"
    user_info_provider_service: "test_user_provider_service"
    event_processor_service: "test_event_processor_service"
    events:
        patient.archive:
            message: "Patient has been archived."
        patient.add:
            message: "Patient has been archived."
');
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $rawConfig);
    }
    
    public function testInvalidEventProcessor()
    {
        $this->setExpectedException('RuntimeException');

        $yaml = new Parser();
        $rawConfig = $yaml->parse('
audit_log:
    connection:
        host: "localhost"
        username: "root"
        password: "password"
        database: "database"
    user_info_provider_service: "test_user_provider_service"
    event_processor_service: ~
    events:
        patient.archive:
            message: "Patient has been archived."
        patient.add:
            message: "Patient has been archived."
');
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $rawConfig);
    }
    
    public function testInvalidUserProvider()
    {
        $this->setExpectedException('RuntimeException');

        $yaml = new Parser();
        $rawConfig = $yaml->parse('
audit_log:
    connection:
        host: "localhost"
        username: "root"
        password: "password"
        database: "database"
    user_info_provider_service: ~
    event_processor_service: "test_event_processor"
    events:
        patient.archive:
            message: "Patient has been archived."
        patient.add:
            message: "Patient has been archived."
');
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $rawConfig);
    }    
    
}