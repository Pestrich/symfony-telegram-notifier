<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\GetMeRequestPayload;
use App\Dto\SendMessageRequestPayload;
use App\Service\TelegramServiceInterface;
use App\Utils\ApiResponseBody;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[AsController]
final class TelegramController
{
    public function __construct(
        private readonly TelegramServiceInterface $telegramService,
        private readonly ApiResponseBody $responseBody,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route(
        path: '/get-me',
        methods: 'POST',
    )]
    public function getMe(Request $request): JsonResponse
    {
        try {
            $result = $this->telegramService->getMe(
                GetMeRequestPayload::createFromRequest($request)->token,
            );

            if ($result) {
                $this->responseBody->setPayload($result);
                $this->responseBody->setSuccess(true);
            }
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                ...$request->request->all(),
            ]);

            $this->responseBody->setError($exception->getMessage());
        }

        return new JsonResponse($this->responseBody);
    }

    #[Route(
        path: '/send-message',
        methods: 'POST',
    )]
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $result = $this->telegramService->sendMessage(
                SendMessageRequestPayload::createFromRequest($request),
            );

            $this->responseBody->setSuccess($result);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                ...$request->request->all(),
            ]);

            $this->responseBody->setError($exception->getMessage());
        }

        return new JsonResponse($this->responseBody);
    }
}
