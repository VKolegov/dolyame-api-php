<?php

namespace VKolegov\DolyameAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\UuidFactory;
use VKolegov\DolyameAPI\Entities\OrderInfo;
use VKolegov\DolyameAPI\Entities\RefundResponse;
use VKolegov\DolyameAPI\Exceptions\DolyameRequestException;
use VKolegov\DolyameAPI\Requests\CommitOrderRequest;
use VKolegov\DolyameAPI\Requests\CreateOrderRequest;
use VKolegov\DolyameAPI\Requests\RefundRequest;

class DolyameAPIClient
{
    private const BASE_URL = "https://partner.dolyame.ru/";
    private int $v;
    private string $apiURL;
    private Client $client;

    private array $certificateOptions;

    /**
     * @param string $login
     * @param string $password
     * @param string $mtlsCertPath full path to MTLS certificate
     * @param string $sslKeyPath full path to SSL key
     * @param int $v API version
     */
    public function __construct(
        string $login,
        string $password,
        string $mtlsCertPath,
        string $sslKeyPath,
        int    $v = 1
    )
    {
        $this->v = $v;
        $this->apiURL = self::BASE_URL . "v$v/"; // e.g. https://partner.dolyame.ru/v1/

        $this->certificateOptions = [
            RequestOptions::CERT => $mtlsCertPath,
            RequestOptions::SSL_KEY => $sslKeyPath,
        ];

        $credentials = base64_encode("$login:$password");

        $this->client = new Client(
            [
                'base_uri' => $this->apiURL,
                RequestOptions::HEADERS => [
                    'Authorization' => "Basic $credentials"
                ]
            ]
        );
    }

    /**
     * @return int
     */
    public function getApiVersion(): int
    {
        return $this->v;
    }

    /**
     * @param string $endpoint
     * @return \GuzzleHttp\Psr7\Response
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function makeGetRequest(string $endpoint): Response
    {
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return \GuzzleHttp\Psr7\Response
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function makePostRequest(string $endpoint, array $data = []): Response
    {
        return $this->makeRequest('POST', $endpoint, $data);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return \GuzzleHttp\Psr7\Response
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    private function makeRequest(string $method, string $endpoint, array $data = []): Response
    {
        $uuidFactory = new UuidFactory();
        $uuid = $uuidFactory->uuid4();

        $options = $this->certificateOptions + [
                RequestOptions::HEADERS => [
                    'X-Correlation-ID' => $uuid->toString(),
                    'Content-Type' => 'application/json'
                ],
            ];

        if ($method === 'POST' && !empty($data)) {
            $options[RequestOptions::BODY] = json_encode(
                $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS
            );
        }

        try {
            return $this->client->request(
                $method,
                $endpoint,
                $options
            );
        } catch (ClientException $e) {
            // TODO: throw meaningful exceptions
            if ($e->hasResponse()) {

                $responseBodyJSON = json_decode(
                    $e->getResponse()->getBody()->getContents(),
                    true
                );

                if (!$responseBodyJSON) {
                    $responseBodyJSON = [];
                }

                throw DolyameRequestException::withResponse($responseBodyJSON, $e->getCode());
            }

            throw $e;
        } catch (GuzzleException $e) {
            throw new DolyameRequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Метод создания заказа
     * https://dolyame.ru/develop/help/api/?method=create
     * @param \VKolegov\DolyameAPI\Requests\CreateOrderRequest $request
     * @return \VKolegov\DolyameAPI\Entities\OrderInfo
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function createOrder(CreateOrderRequest $request): OrderInfo
    {
        $response = $this->makePostRequest('orders/create', $request->toArray());

        $responseBody = $response->getBody()->getContents();
        $responseBodyJSON = json_decode($responseBody, true);

        return OrderInfo::fromArray($responseBodyJSON);
    }

    /**
     * Метод для подтверждения заказа
     * https://dolyame.ru/develop/help/api/?method=commit
     * @param \VKolegov\DolyameAPI\Requests\CommitOrderRequest $request
     * @return \VKolegov\DolyameAPI\Entities\OrderInfo
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function commitOrder(CommitOrderRequest $request): OrderInfo
    {
        $id = $request->getId();
        $response = $this->makePostRequest("orders/$id/commit", $request->toArray());

        $responseBody = $response->getBody()->getContents();
        $responseBodyJSON = json_decode($responseBody, true);

        return OrderInfo::fromArray($responseBodyJSON);
    }

    /**
     * Метод для отмены заказа
     * https://dolyame.ru/develop/help/api/?method=cancel
     * @param string $id
     * @return \VKolegov\DolyameAPI\Entities\OrderInfo
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function cancelOrder(string $id): OrderInfo
    {
        $response = $this->makePostRequest("orders/$id/cancel");

        $responseBody = $response->getBody()->getContents();
        $responseBodyJSON = json_decode($responseBody, true);

        return OrderInfo::fromArray($responseBodyJSON);
    }

    /**
     * Метод получения актуальной информации по заказу
     * https://dolyame.ru/develop/help/api/?method=info
     * @param string $id
     * @return \VKolegov\DolyameAPI\Entities\OrderInfo
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function orderInfo(string $id): OrderInfo
    {
        $response = $this->makeGetRequest("orders/$id/info");

        $responseBody = $response->getBody()->getContents();
        $responseBodyJSON = json_decode($responseBody, true);

        return OrderInfo::fromArray($responseBodyJSON);
    }

    /**
     * Метод для совершения возврата по заказу
     * https://dolyame.ru/develop/help/api/?method=refund
     * @param \VKolegov\DolyameAPI\Requests\RefundRequest $request
     * @return \VKolegov\DolyameAPI\Entities\RefundResponse
     * @throws \VKolegov\DolyameAPI\Exceptions\DolyameRequestException
     */
    public function refund(RefundRequest $request): RefundResponse
    {
        $id = $request->getId();
        $response = $this->makePostRequest("orders/$id/refund", $request->toArray());

        $responseBody = $response->getBody()->getContents();
        $responseBodyJSON = json_decode($responseBody, true);

        return RefundResponse::fromArray($responseBodyJSON);
    }
}