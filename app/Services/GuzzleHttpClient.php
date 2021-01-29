<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Class GuzzleHttpClient
 * @package App\Services
 * @author Nourhan
 */
class GuzzleHttpClient
{
    /**
     * indicator of successfully request
     */
    const SUCCESS = true;
    /**
     * indicator of failed request
     */
    const FAILED = false;
    /**
     * @param array $headers
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function get(array $headers, string $uri, array $options): array
    {
        try {
            $client = new Client([
                'headers' => $headers
            ]);

            $response = $client->get(
                $uri,
                $options
            );

            return [
                'response' => json_decode(
                    $response->getBody()->getContents(),
                    true
                ),
                'status' => self::SUCCESS
            ];
        } catch (\Exception $exception) {
            return [
                'response' => ['message'=>$exception->getMessage()],
                'status' => self::FAILED
            ];
        }
    }
}
