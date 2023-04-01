<?php

namespace AlexMorbo\BasIpApiClient\Endpoints;

use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use AlexMorbo\BasIpApiClient\v1\Dto\Login;

trait AuthTrait
{
    public function login(string $login, string $password): Login|Error
    {
        return $this->getJson(
            sprintf(
                '%s/login?username=%s&password=%s',
                self::VERSION_PREFIX,
                $login,
                md5($password)
            ),
            mapTo: Login::class
        );
    }
}