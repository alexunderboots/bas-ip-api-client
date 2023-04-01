<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Network;

use SergiX44\Hydrator\Annotation\Alias;

class Mac
{
    #[Alias('mac_address')]
    public string $macAddress;
}