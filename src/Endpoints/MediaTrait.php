<?php

namespace AlexMorbo\BasIpApiClient\Endpoints;


use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use AlexMorbo\BasIpApiClient\v1\Dto\Media\Snapshot;

trait MediaTrait
{
    public function getCameraSnapshot(): Snapshot|Error
    {
        return $this->getRaw(
            sprintf('%s/photo/file', self::VERSION_PREFIX),
            mapTo: Snapshot::class,
            login: $this->getAuth()
        );
    }

}