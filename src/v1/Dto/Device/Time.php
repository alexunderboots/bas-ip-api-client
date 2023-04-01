<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Device;

use SergiX44\Hydrator\Annotation\Alias;

class Time
{
    #[Alias('time_unix')]
    public int $timeUnix;
    public string $timezone;
}