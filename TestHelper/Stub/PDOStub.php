<?php

namespace Naldz\Bundle\AuditLogBundle\TestHelper\Stub;

class PDOStub
{
    private $conString;
    private $user;
    private $pass;
    
    public function __construct($conString, $user, $password)
    {
        $this->conString = $conString;
        $this->user = $user;
        $this->password = $password;
    }
    
    public function getConnectionString()
    {
        return $this->conString;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function prepare()
    {
        
    }
}