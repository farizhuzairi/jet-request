<?php

namespace Jet\Request\Client\Supports;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Jet\Request\Client\Contracts\Requestionable;
use Jet\Request\Client\Http\Exception\JetRequestException;

class InvalidResponse
{
    public function __invoke(Requestionable $request, ?Response $response)
    {
        return $this->invalid_response($request, $response);
    }

    protected function invalid_response(Requestionable $request, ?Response $response): Response
    {
        if($response instanceof Response) {

            report(new JetRequestException(
                "Unable to recognize http response. Allows for resource errors or server failures to occur.",
                [
                    'request' => $request?->getUrl(),
                    'method' => $request?->getMethod(),
                    'error_details' => $response->collect()->only(['message', 'exception', 'file', 'line'])->toArray()
                ]
            ));

        }

        if($response === null) {

            report(new JetRequestException(
                "Bad Request. Http request error on client server.",
                400,
                [
                    'request' => $request?->getUrl(),
                    'method' => $request?->getMethod(),
                    'error_details' => []
                ]
            ));

            $jsonResponse = response()->json([
                'successful' => false,
                'statusCode' => 400,
                'message' => "Bad Request. Data not found.",
                'results' => [],
                'meta' => []
            ], 400);

        }

        else {

            $jsonResponse = response()->json([
                'successful' => false,
                'statusCode' => 500,
                'message' => "Server Error. Unable to recognize http response. Allows for resource errors or server failures to occur.",
                'results' => [],
                'meta' => []
            ], 500);

        }

        return new Response(
            new \GuzzleHttp\Psr7\Response(
                $jsonResponse->getStatusCode(),
                $jsonResponse->headers->all(),
                json_encode($jsonResponse->getData())
            ),
            Http::fake()
        );
    }
}