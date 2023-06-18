<?php

namespace App\Service;

use App\Exception\ClientException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class BookClient
{
    private const DEFAULT_CLIENT_OPTIONS = [
        'connect_timeout' => 10,
        'timeout' => 20,
        'http_errors' => true,
    ];

    private const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json',
    ];

    public function __construct(
        private ClientInterface $client,
        private LoggerInterface $logger,
        private string $baseUri,
    ) {
    }

    public function request(string $method, string $path, array $options = [], ?array $payload = []): ResponseInterface
    {
        $headers = \array_merge($options, self::DEFAULT_HEADERS, self::DEFAULT_CLIENT_OPTIONS);
        $headers = $payload ? \array_merge($headers, ['form_params' => ['data' => $payload]]) : $headers;

        try {
            $url = $this->createUri($path);

            return $this->client->request($method, $url, $headers);
        } catch (GuzzleException $exception) {
            $message = sprintf("An exception occurred while calling '%s'", $url);
            $this->logger->alert($message, ['trace' => $exception->getTraceAsString()]);

            throw new ClientException($message, 0, $exception);
        }
    }

    public function get(string $url): ResponseInterface
    {
        $response = $this->request('GET', $url, []);

        return $response;
    }

    private function createUri(string $path): string
    {
        $baseUri = \rtrim($this->baseUri, '/');
        $pathUri = \ltrim($path, '/');

        return sprintf('%s/%s', $baseUri, $pathUri);
    }
}
