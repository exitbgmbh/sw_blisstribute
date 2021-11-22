<?php

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP client for the Blisstribute REST API.
 *
 * @package Shopware\Components\Blisstribute\RestClient
 * @since   1.0.0
 */
class Shopware_Components_Blisstribute_RestClient
{
    private $httpClient;
    const API_VERSION = 'v1';

    private $header = [
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Construct a new Blisstribute REST client.
     *
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        if (defined('GuzzleHttp\Client::VERSION')) {
            $this->httpClient = new Client([
                'base_url' => sprintf('%s/%s/', $baseUrl, self::API_VERSION),
                'defaults' => [
                    'timeout' => 30,
                    'headers' => [
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                ]
            ]);
        } else {
            $this->httpClient = new Client([
                'base_uri' => sprintf('%s/%s/', $baseUrl, self::API_VERSION),
            ]);
        }
    }

    /**
     * Create a new resource.
     *
     * @param $path
     * @param array $data
     * @param array $query
     * @param array $header
     * @return ResponseInterface
     */
    public function post($path, $data = [], $query = [])
    {
        return $this->httpClient
            ->post($path, ['json' => $data, 'query' => $query, 'headers' => $this->header]);
    }

    /**
     * Authenticate using an API Key. Stores a JWT token internally, which is valid for 8 hours and must be refreshed
     * afterwards.
     *
     * @param string $apiKey
     * @return void
     * @throws Exception
     */
    public function authenticateWithApiKey(string $client, string $apiKey): string
    {
        $this->authenticate(['client' => $client, 'apiKey' => $apiKey]);
    }

    /**
     * Authenticate using an API Key. Stores a JWT token internally, which is valid for 8 hours and must be refreshed
     * afterwards.
     *
     * @param string $client
     * @param string $user
     * @param string $password
     * @return void
     * @throws Exception
     */
    public function authenticateWithClientUserPassword(string $client, string $user, string $password): string
    {
        $this->authenticate([
            'client'   => $client,
            'user'     => $user,
            'password' => $password,
        ]);
    }

    /**
     * @param array $credentials
     * @return void
     * @throws Exception
     */
    private function authenticate(array $credentials): string
    {
        if (array_key_exists('Authorization', $this->header)) {
            return;
        }

        $response = json_decode($this->post('login/authenticate', $credentials)->getBody()->getContents(), true);
        $token    = $response['response']['jwt'] ?? false;

        if (!$token) {
            throw new Exception('Response missing token');
        }

        $this->header['Authorization'] = 'Bearer ' . $token;
    }

    /**
     * Creates or updates one or multiple products, depending on whether a product contains a field 'vhsArticleNumber'
     * with a unique VHS identifier or not.
     *
     * @param array $products
     * @return ResponseInterface
     */
    public function createOrUpdateProduct(array $products)
    {
        return $this->post('product/createOrUpdate', ['productData' => $products]);
    }

    /**
     * Creates a single order.
     *
     * @param array $order
     * @return ResponseInterface
     */
    public function createOrder(array $order)
    {
        return $this->post('order/create', ['orderData' => $order]);
    }
}
