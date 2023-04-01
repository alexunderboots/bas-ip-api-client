<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto;

use AlexMorbo\BasIpApiClient\LoginInterface;
use SergiX44\Hydrator\Annotation\Alias;

class Login implements LoginInterface
{
    #[Alias('account_type')]
    public ?string $accountType = null;

    public string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public static function make(string $token): LoginInterface
    {
        $login = new self();
        $login->token = $token;

        return $login;
    }
}