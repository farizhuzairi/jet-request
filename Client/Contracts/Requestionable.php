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

    public function getDataWrapperName(): ?string;
    public function getDataWrapper(): array;

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

    public function results(): array;
    public function getResults(): array;

    public function successful(): bool;
    public function getSuccessful(): bool;

    public function statusCode(): int;
    public function getStatusCode(): int;

    public function message(): ?string;
    public function getMessage(): ?string;

    public function getOriginalResponse(): array;

    public function __call($name, $arguments);
}