<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Device;

use SergiX44\Hydrator\Annotation\Alias;

class Language
{
    #[Alias('current_language')]
    public string $currentLanguage;
    #[Alias('all_supported_languages')]
    public array $allSupportedLanguages;
}