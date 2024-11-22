<?php

namespace AlexMorbo\BasIpApiClient;

use AlexMorbo\BasIpApiClient\Exceptions\BasIpException;
use AlexMorbo\BasIpApiClient\Hydrator\BasIpHydrator;
use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use Psr\Log\LoggerInterface;

class AbstractClient
{
    protected \GuzzleHttp\ClientInterface $http;
    protected BasIpHydrator $mapper;
    protected array $config;
    private ?LoginInterface $auth = null;

    public function __construct(string $host, array $config, protected LoggerInterface $logger, protected BasIp $basIp)
    {
        /**
         * Check host, remove last slash if exists
         */
        if (substr($host, -1) === '/') {
            $host = substr($host, 0, -1);
        }

        $config['base_uri'] = rtrim($host, '/') . '/api/';
        $this->config = $config;
    }

    public function getHttp(): \GuzzleHttp\ClientInterface
    {
        return $this->http;
    }

    public function setAuth(LoginInterface $login)
    {
        $this->auth = $login;

        if (is_callable($this->config['authCallback'])) {
            $callback = $this->config['authCallback'];
            call_user_func($callback, $login);
        }
    }

    public function getAuth(): LoginInterface
    {
        if ($this->auth === null && $this->basIp->getCredentials() === null) {
            throw new BasIpException('Empty auth token, you need call login method or set token manualy');
        }
        if ($this->basIp->getCredentials() instanceof Credentials) {
            $auth = $this->basIp->login(
                $this->basIp->getCredentials()->login,
                $this->basIp->getCredentials()->password,
            );
            if ($auth instanceof LoginInterface) {
                $this->setAuth($auth);
            }
            if ($auth instanceof Error) {
                throw new BasIpException($auth->error);
            }
        }
        return $this->auth;
    }
}