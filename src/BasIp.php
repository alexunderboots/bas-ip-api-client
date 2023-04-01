<?php

namespace AlexMorbo\BasIpApiClient;

use AlexMorbo\BasIpApiClient\Enum\ApiVersionEnum;
use AlexMorbo\BasIpApiClient\Exceptions\BasIpException;
use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class BasIp
{
    private ClientInterface $client;

    private LoggerInterface $logger;

    private ?Credentials $credentials = null;

    /**
     * @throws BasIpException
     */
    public function __construct(string $host, protected int $apiVersion = ApiVersionEnum::v1->value, array $config = [])
    {
        $hostData = parse_url($host);
        if (count($hostData) === 1 && isset($hostData['path'])) {
            $host = sprintf('http://%s', $hostData['path']);
        } else {
            $host = sprintf('%s://%s', $hostData['scheme'] ?? 'http', $hostData['host']);
        }

        $this->logger = $config['logger'] ?? new NullLogger();
        $this->client = match ($this->apiVersion) {
            ApiVersionEnum::v1->value => new v1\Client($host, $config, $this->logger, $this),
            ApiVersionEnum::v2->value => throw new BasIpException('Not Implemented'),
            default => throw new BasIpException('Unknown API version'),
        };

        $this->client->initHttp();

        return $this;
    }

    public function setCredentials(string $login, string $password): void
    {
        $this->credentials = new Credentials();
        $this->credentials->login = $login;
        $this->credentials->password = $password;
    }

    public function getCredentials(): ?Credentials
    {
        return $this->credentials;
    }

    public function auth(LoginInterface $login): void
    {
        $this->client->setAuth($login);
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws BasIpException
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->client, $method)) {
            $result = $this->client->$method(...$parameters);
            if (
                $result instanceof Error &&
                $result->error === 'Log In' &&
                $this->credentials !== null
            ) {
                $this->auth(
                    $this->login($this->credentials->login, $this->credentials->password)
                );

                return $this->$method(...$parameters);
            }

            return $result;
        }

        throw new BasIpException('Method not found');
    }
}