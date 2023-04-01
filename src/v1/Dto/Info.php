<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto;

use SergiX44\Hydrator\Annotation\Alias;

class Info
{
    #[Alias('firmware_version')]
    public string $firmwareVersion;
    #[Alias('framework_version')]
    public string $frameworkVersion;
    #[Alias('frontend_version')]
    public ?string $frontendVersion = null;
    #[Alias('api_version')]
    public string $apiVersion;
    #[Alias('device_name')]
    public string $deviceName;
    #[Alias('device_type')]
    public string $deviceType;
    #[Alias('device_serial_number')]
    public string $deviceSerialNumber;
    #[Alias('hybrid_enable')]
    public bool $hybridEnable;
    #[Alias('hybrid_version')]
    public ?string $hybridVersion = null;
    public string $commit;
}