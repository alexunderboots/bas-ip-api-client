<?php

namespace AlexMorbo\BasIpApiClient\Endpoints;


use AlexMorbo\BasIpApiClient\v1\Dto\Access\InputCode;
use AlexMorbo\BasIpApiClient\v1\Dto\Error;

trait AccessTrait
{
    public function getAccessInputCode(): InputCode|Error
    {
        return $this->getJson(
            sprintf('%s/access/general/unlock/input/code', self::VERSION_PREFIX),
            mapTo: InputCode::class,
            login: $this->getAuth()
        );
    }

    public function openLock(int $lockNumber = 0): bool
    {
        return $this->requestHttp(
            sprintf('%s/access/general/lock/open/remote/accepted/%d', self::VERSION_PREFIX, $lockNumber),
            login: $this->getAuth(),
            method: 'GET'
        );
    }

}