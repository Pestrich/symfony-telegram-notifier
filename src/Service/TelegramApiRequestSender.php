<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\TelegramResponse;
use App\Enum\Endpoint;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Log\LoggerInterface;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final class TelegramApiRequestSender implements TelegramApiRequestSenderInterface
{
    private string $token;

    public function __construct(
        private readonly string $url,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Endpoint $endpoint, array $params = []): ?TelegramResponse
    {
        $request = TelegramApiRequestFactory::create($endpoint);
        $options = $this->getOptions($params);
        $client = $this->getClient();

        try {
            $response = $client->send($request, $options);

            $contents = $response->getBody()->getContents();

            $decodedContents = json_decode(
                $contents,
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (GuzzleException $exception) {
            $this->logger->error(
                "Не удалось выполнить запрос $endpoint->value", [
                'payload' => [
                    'message' => $exception->getMessage(),
                    'params' => $params,
                ],
            ]);

            return null;
        } catch (JsonException $exception) {
            $this->logger->error("Некорректный ответ от сервера при выполнении запроса $endpoint->value", [
                'payload' => [
                    'message' => $exception->getMessage(),
                    'params' => $params,
                ],
            ]);

            return null;
        }

        return new TelegramResponse($decodedContents);
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function getOptions(array $params = []): array
    {
        return [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
            ],
            RequestOptions::JSON => $params,
            RequestOptions::TIMEOUT => 1,
            RequestOptions::CONNECT_TIMEOUT => 1,
        ];
    }

    private function getClient(): Client
    {
        return new Client(['base_uri' => "$this->url/bot$this->token/"]);
    }
}
