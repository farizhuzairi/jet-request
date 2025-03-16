<?php

namespace Jet\Request\Client\Supports;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait UseInvalidResponse
{
    protected function invalidResponse(?Response $response = null): Response
    {
        if($response instanceof Response) {

            Log::error(
                "Resource error or server failure.",
                [
                    'request' => $this->getUrl(),
                    'method' => $this->getMethod(),
                    'data' => $this->getData(),
                    'error_details' => $response->collect()->only(['message', 'exception', 'file', 'line'])->toArray()
                ]
            );

        }

        if($response === null) {

            Log::error(
                "Bad Request. Http request error on internal server.",
                [
                    'request' => $this->getUrl(),
                    'method' => $this->getMethod(),
                    'data' => $this->getData(),
                    'error_details' => []
                ]
            );

            $jsonResponse = response()->json([
                'successful' => false,
                'statusCode' => 400,
                'message' => "Bad Request. Data not found.",
                'results' => []
            ], 400);

        }
        else {
            $jsonResponse = response()->json([
                'successful' => false,
                'statusCode' => 500,
                'message' => "There was a problem with the internal server.",
                'results' => []
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