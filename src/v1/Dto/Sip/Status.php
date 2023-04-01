<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Sip;

use SergiX44\Hydrator\Annotation\Alias;

class Status
{
    #[Alias('sip_status')]
    public string $sipStatus;
}