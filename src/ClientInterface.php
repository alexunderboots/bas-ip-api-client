<?php

namespace AlexMorbo\BasIpApiClient;

interface ClientInterface
{
    public function initHttp(): void;
    public function getHttp(): \GuzzleHttp\ClientInterface;
}