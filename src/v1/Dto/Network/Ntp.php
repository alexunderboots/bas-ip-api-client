<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Network;

use SergiX44\Hydrator\Annotation\Alias;

class Ntp
{
    public bool $enabled;
    #[Alias('use_default')]
    public ?bool $useDefault = null;
    #[Alias('custom_server')]
    public ?string $customServer = null;
}