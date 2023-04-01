<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Network;

use SergiX44\Hydrator\Annotation\Alias;

class Settings
{
    public bool $dhcp;
    #[Alias('ip_address')]
    public string $ipAddress;
    public string $mask;
    public string $gateway;
    public string $dns;
}