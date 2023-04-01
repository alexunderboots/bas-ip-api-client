<?php

namespace AlexMorbo\BasIpApiClient;

interface LoginInterface
{
    public function getToken(): string;
    public static function make(string $token): self;
}