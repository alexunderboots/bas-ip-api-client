<?php

namespace AlexMorbo\BasIpApiClient\Endpoints;

use AlexMorbo\BasIpApiClient\v1\Dto\Device\Language;
use AlexMorbo\BasIpApiClient\v1\Dto\Device\Time;
use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use AlexMorbo\BasIpApiClient\v1\Dto\Info;
use AlexMorbo\BasIpApiClient\v1\Dto\Sip\Status;

trait StatusTrait
{
    public function getInfo(): Info
    {
        return $this->getJson('info', mapTo: Info::class);
    }

    public function getDeviceLanguage(): Language|Error
    {
        return $this->getJson(
            sprintf('%s/device/language', self::VERSION_PREFIX),
            mapTo: Language::class,
            login: $this->getAuth()
        );
    }

    public function setDeviceLanguage(string $language): bool|Error
    {
        return $this->requestHttp(
            sprintf('%s/device/language?language=%s', self::VERSION_PREFIX, $language),
            login: $this->getAuth()
        );
    }

    public function getSipStatus(): Status|Error
    {
        return $this->getJson(
            sprintf('%s/sip/status', self::VERSION_PREFIX),
            mapTo: Status::class,
            login: $this->getAuth()
        );
    }

    public function getDeviceTime(): Time|Error
    {
        return $this->getJson(
            sprintf('%s/device/time', self::VERSION_PREFIX),
            mapTo: Time::class,
            login: $this->getAuth()
        );
    }

}