<?php

namespace Naldz\Bundle\AuditLogBundle\User;

interface UserInfoProviderInterface
{
    public function getId();
    
    public function getName();
}