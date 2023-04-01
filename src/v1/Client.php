<?php

namespace AlexMorbo\BasIpApiClient\v1;

use AlexMorbo\BasIpApiClient\AbstractClient;
use AlexMorbo\BasIpApiClient\ClientInterface;
use AlexMorbo\BasIpApiClient\Endpoints\AccessTrait;
use AlexMorbo\BasIpApiClient\Endpoints\AuthTrait;
use AlexMorbo\BasIpApiClient\Endpoints\MediaTrait;
use AlexMorbo\BasIpApiClient\Endpoints\NetworkTrait;
use AlexMorbo\BasIpApiClient\Endpoints\StatusTrait;
use AlexMorbo\BasIpApiClient\Enum\ApiVersionEnum;
use AlexMorbo\BasIpApiClient\Exceptions\BasIpException;
use AlexMorbo\BasIpApiClient\Hydrator\BasIpHydrator;
use AlexMorbo\BasIpApiClient\LoginInterface;
use AlexMorbo\BasIpApiClient\v1\Dto\Error;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class Client extends AbstractClient implements ClientInterface
{
    use AuthTrait, StatusTrait, NetworkTrait, AccessTrait, MediaTrait;

    const VERSION_PREFIX = 'v' . ApiVersionEnum::v1->value;

    public function initHttp(): void
    {
        $this->http = new \GuzzleHttp\Client($this->config);
        $this->mapper = new BasIpHydrator();
    }

    protected function getRaw(
        string $endpoint,
        string $mapTo = stdClass::class,
        array $options = [],
        ?LoginInterface $login = null,
    ): mixed {
        try {
            if ($login) {
                $response = $this->http->get($endpoint, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $login->getToken()
                    ]
                ]);
            } else {
                $response = $this->http->get($endpoint);
            }

            return $this->mapper->hydrate(['raw' => $response->getBody()->getContents()], $mapTo);
        } catch (RequestException $exception) {
            if (!$exception->hasResponse()) {
                throw $exception;
            }
            return $this->mapResponse($exception->getResponse(), Error::class, $exception, $options);
        } catch (GuzzleException $exception) {
            $this->logger->critical('HTTP Exception: ' . $exception->getMessage());

            throw $exception;
        }
    }

    protected function getJson(
        string $endpoint,
        string $mapTo = stdClass::class,
        array $options = [],
        ?LoginInterface $login = null,
    ): mixed {
        try {
            if ($login) {
                $response = $this->http->get($endpoint, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $login->getToken()
                    ]
                ]);
            } else {
                $response = $this->http->get($endpoint);
            }

            $content = $this->mapResponse($response, $mapTo, options: $options);

            $rawResponse = (string)$response->getBody();

            $this->logger->debug($endpoint . PHP_EOL . $rawResponse, [
                'options' => $options
            ]);

            return $content;
        } catch (RequestException $exception) {
            if (!$exception->hasResponse()) {
                throw $exception;
            }
            return $this->mapResponse($exception->getResponse(), Error::class, $exception, $options);
        } catch (GuzzleException $exception) {
            $this->logger->critical('HTTP Exception: ' . $exception->getMessage());

            throw $exception;
        }
    }

    protected function requestJson(
        string $endpoint,
        ?array $json = null,
        string $mapTo = stdClass::class,
        array $options = [],
        ?LoginInterface $login = null,
    ): mixed {
        try {
            $request = array_merge([
                'json' => $json,
            ], $options);

            try {
                if ($login) {
                    $request = array_merge([
                        'headers' => [
                            'Authorization' => 'Bearer ' . $login->getToken()
                        ],
                    ], $options);
                }
                $response = $this->http->post($endpoint, $requestPost ?? $request);
            } catch (ConnectException $e) {
                // TODO: remove auth token from log
                throw $e;
            }
            $content = $this->mapResponse($response, $mapTo, options: $options);

            $rawResponse = (string)$response->getBody();
            $this->logger->debug($endpoint . PHP_EOL . $rawResponse, [
                'parameters' => $json,
                'options' => $options
            ]);

            return $content;
        } catch (RequestException $exception) {
            if (!$exception->hasResponse()) {
                throw $exception;
            }
            return $this->mapResponse($exception->getResponse(), $mapTo, $exception, $options);
        }
    }

    public function requestHttp(
        string $endpoint,
        ?array $json = null,
        int $expectationCode = 200,
        array $options = [],
        ?LoginInterface $login = null,
        $method = 'POST',
    ): bool
    {
        try {
            $request = array_merge([
                'json' => $json,
            ], $options);

            try {
                if ($login) {
                    $request = array_merge([
                        'headers' => [
                            'Authorization' => 'Bearer ' . $login->getToken()
                        ],
                    ], $options);
                }
                $response = $this->http->request($method, $endpoint, $requestPost ?? $request);
            } catch (ConnectException $e) {
                // TODO: remove auth token from log
                throw $e;
            }

            $responseStatusCode = $response->getStatusCode();

            $this->logger->debug($endpoint . PHP_EOL . $responseStatusCode, [
                'parameters' => $json,
                'options' => $options
            ]);

            return $responseStatusCode === $expectationCode;
        } catch (RequestException $exception) {
            if (!$exception->hasResponse()) {
                throw $exception;
            }

            return false;
        }
    }

    protected function mapResponse(
        ResponseInterface $response,
        string $mapTo,
        Exception $clientException = null,
        array $options = []
    ): mixed {
        try {
            $json = json_decode(
                json: (string)$response->getBody(),
                associative: true,
                depth: 512,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new BasIpException(
                message: 'Invalid JSON response',
                code: 0,
                previous: $e,
            );
        }

        if ($json) {
            return match (true) {
                is_scalar($json) => $json,
                isset($options['array']) && $options['array'] => $this->mapper->hydrateArray($json, $mapTo),
                default => $this->mapper->hydrate($json, $mapTo)
            };
        }

        throw new BasIpException(
            message: $json?->description ?? 'Client exception',
            code: $json?->error_code ?? 0,
            previous: $clientException,
            parameters: (array)($json?->parameters ?? []),
        );
    }
}