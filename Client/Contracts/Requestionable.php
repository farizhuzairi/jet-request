<?php

namespace Jet\Request\Client\Contracts;

use Closure;
use Illuminate\Http\Client\Response;

interface Requestionable
{
    public function __construct(
        array $data,
        ?string $method,
        ?string $accept
    );

    public function data(array $data = []): static;
    public function setData(array $data): void;
    public function getData(): array;

    public function method(?string $method = null): static;
    public function setMethod(string $method): void;
    public function getMethod(): string;

    public function accept(?string $accept = null): static;
    public function setAccept(string $accept): void;
    public function getAccept(): string;

    public function api(?Closure $request): static;

    public function response(): Response;
    public function getResponse(): Response;

    public function results(array|string|null $key = null): array;
    public function getResults(array|string|null $key = null): array;

    public function successful(): bool;
    public function getSuccessful(): bool;

    public function statusCode(): int;
    public function getStatusCode(): int;

    public function message(): ?string;
    public function getMessage(): ?string;

    public function getOriginalResults(bool $isHttp = false): array;

    // Use Hostable
    public function httpHost(): string;
    public function endpoint(): string;
    public function version(): string;
    public function topics(?string $topics = null): static;
    public function getTopics(): string;
    public function url(?string $url = "", bool $isNewUrl = false): static;
    public function getUrl(): string;
    public function header(array|string $header, mixed $value = null): static;
    public function getHeader(?string $key = null): array;
    public function headers(array $header): static;
    public function getHeaders(): array;
}