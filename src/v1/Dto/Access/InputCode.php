<?php

namespace AlexMorbo\BasIpApiClient\v1\Dto\Access;

use SergiX44\Hydrator\Annotation\Alias;

class InputCode
{
    #[Alias('input_code_enable')]
    public bool $inputCodeEnable;
    #[Alias('input_code_number')]
    public string $inputCodeNumber;
}