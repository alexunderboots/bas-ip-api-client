<?php

namespace AlexMorbo\BasIpApiClient\Endpoints;

use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use AlexMorbo\BasIpApiClient\v1\Dto\Network\Mac;
use AlexMorbo\BasIpApiClient\v1\Dto\Network\Ntp;
use AlexMorbo\BasIpApiClient\v1\Dto\Network\Settings;

trait NetworkTrait
{
    public function getNetworkSettings(): Settings|Error
    {
        return $this->getJson(
            sprintf('%s/network/settings', self::VERSION_PREFIX),
            mapTo: Settings::class,
            login: $this->getAuth()
        );
    }

    public function getNetworkMac(): Mac|Error
    {
        return $this->getJson(
            sprintf('%s/network/mac', self::VERSION_PREFIX),
            mapTo: Mac::class,
            login: $this->getAuth()
        );
    }

    public function getNetworkNtp(): Ntp|Error
    {
        return $this->getJson(
            sprintf('%s/network/ntp', self::VERSION_PREFIX),
            mapTo: Ntp::class,
            login: $this->getAuth()
        );
    }

}