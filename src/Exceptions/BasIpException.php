<?php

namespace AlexMorbo\BasIpApiClient\Exceptions;

use Exception;

class BasIpException extends Exception
{
    protected array $parameters;

    public function __construct($message, $code = 0, $previous = null, array $parameters = [])
    {
        $this->parameters = $parameters;
        parent::__construct($message, $code, $previous);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }

    public function hasParameter(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }
}