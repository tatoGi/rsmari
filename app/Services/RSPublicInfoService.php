<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class RSPublicInfoService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false, // For SSL issues if any
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Laravel/TaxPayerApp'
            ]
        ]);

        $this->baseUrl = 'https://xdata.rs.ge/TaxPayer';
    }

    /**
     * Get public information for a taxpayer by IdentCode
     *
     * @param string $identCode
     * @return array
     */
    public function getPublicInfo(string $identCode): array
    {
        try {
            $response = $this->client->post($this->baseUrl . '/RSPublicInfo', [
                'json' => [
                    'IdentCode' => $identCode
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // Log the response for debugging
            Log::info('RS API Response', [
                'ident_code' => $identCode,
                'status_code' => $statusCode,
                'response' => $body
            ]);

            if ($statusCode === 200) {
                $data = json_decode($body, true);

                // The API returns an array, so we need to handle it properly
                if (is_array($data) && !empty($data)) {
                    $taxpayerData = is_array($data[0]) ? $data[0] : $data;

                    return [
                        'success' => true,
                        'data' => $taxpayerData,
                        'raw_response' => $body
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Empty or invalid response from API',
                        'raw_response' => $body
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error' => 'API returned status code: ' . $statusCode,
                    'raw_response' => $body
                ];
            }

        } catch (RequestException $e) {
            $errorMessage = 'HTTP Request failed: ' . $e->getMessage();

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $body = $e->getResponse()->getBody()->getContents();
                $errorMessage .= ' (Status: ' . $statusCode . ', Body: ' . $body . ')';
            }

            Log::error('RS API Request Exception', [
                'ident_code' => $identCode,
                'error' => $errorMessage,
                'exception' => $e
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'raw_response' => null
            ];

        } catch (\Exception $e) {
            $errorMessage = 'Unexpected error: ' . $e->getMessage();

            Log::error('RS API Unexpected Exception', [
                'ident_code' => $identCode,
                'error' => $errorMessage,
                'exception' => $e
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'raw_response' => null
            ];
        }
    }

    /**
     * Validate IdentCode format
     *
     * @param string $identCode
     * @return bool
     */
    public function validateIdentCode(string $identCode): bool
    {
        // Georgian IdentCode can be:
        // - 9 digits for legal entities
        // - 11 digits for individuals
        return preg_match('/^\d{9}$|^\d{11}$/', $identCode);
    }

    /**
     * Clean and format IdentCode
     *
     * @param string $identCode
     * @return string
     */
    public function cleanIdentCode(string $identCode): string
    {
        // Remove any non-digit characters
        return preg_replace('/\D/', '', $identCode);
    }
}
