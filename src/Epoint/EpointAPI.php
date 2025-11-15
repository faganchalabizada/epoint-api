<?php

namespace FaganChalabizada\Epoint;

use FaganChalabizada\Epoint\Enums\OperationCode;
use FaganChalabizada\Epoint\Enums\PaymentStatus;
use FaganChalabizada\Epoint\Enums\Wallets;
use FaganChalabizada\Epoint\Exception\EpointException;
use FaganChalabizada\Epoint\Response\APIResponse;
use FaganChalabizada\Epoint\Response\CallbackResponse;
use FaganChalabizada\Epoint\Response\CancelOperationResponse;
use FaganChalabizada\Epoint\Response\CreatePaymentResponse;
use FaganChalabizada\Epoint\Response\CreateWalletPaymentResponse;
use FaganChalabizada\Epoint\Response\CreateWidgetResponse;
use FaganChalabizada\Epoint\Response\GetPaymentStatusResponse;
use FaganChalabizada\Epoint\Response\GetWalletStatusResponse;

class EpointAPI
{

    private bool $printResponse = false;
    private string $publicKey;
    private string $privateKey;
    private string $baseUrl = 'https://epoint.az/api/1/';

    public function __construct($publicKey, $privateKey, $printResponse = false)
    {
        $this->printResponse = $printResponse;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    private function generateSignature($data): string
    {
        $signatureString = $this->privateKey . $data . $this->privateKey;
        return base64_encode(sha1($signatureString, true));
    }

    private function encodeData($data): string
    {
        return base64_encode(json_encode($data));
    }


    /**
     * Process the callback request from Epoint.
     *
     * @param string $data The base64-encoded data from the callback.
     * @param string $signature The base64-encoded signature from the callback.
     * @return CallbackResponse The decoded callback data.
     * @throws EpointException If the signature is invalid or data cannot be decoded.
     */
    public function processCallback(string $data, string $signature): CallbackResponse
    {

        $validator = new RequestValidator($this->privateKey);

        // Step 1: Verify the signature
        $isValid = $validator->verifySignature($data, $signature);
        if (!$isValid) {
            throw new EpointException(1, "Invalid signature. The request may have been tampered with.");
        }

        // Step 2: Decode the data
        $decodedData = $this->decodeData($data);

        // Step 3: Map the decoded data to the CallbackResponse class
        return new CallbackResponse(
            orderId: $decodedData['order_id'] ?? null,
            status: isset($decodedData['status']) ? PaymentStatus::from($decodedData['status']) : null,
            code: $decodedData['code'] ?? null,
            message: $decodedData['message']['message'] ?? null,
            transaction: $decodedData['transaction'] ?? null,
            bankTransaction: $decodedData['bank_transaction'] ?? null,
            bankResponse: $decodedData['bank_response'] ?? null,
            operationCode: isset($decodedData['operation_code']) ? OperationCode::from($decodedData['operation_code']) : null,
            rrn: $decodedData['rrn'] ?? null,
            cardName: $decodedData['card_name'] ?? null,
            cardMask: $decodedData['card_mask'] ?? null,
            amount: isset($decodedData['amount']) ? (float)$decodedData['amount'] : null,
            otherAttributes: $decodedData['other_attr'] ?? []
        );
    }


    /**
     * Decode the base64-encoded data.
     *
     * @param string $data The base64-encoded data.
     * @return array The decoded data as an associative array.
     * @throws EpointException If the data cannot be decoded.
     */
    private function decodeData(string $data): array
    {
        $decodedData = base64_decode($data);
        if ($decodedData === false) {
            throw new EpointException(2, "Failed to decode the data.");
        }

        $jsonData = json_decode($decodedData, true);
        if ($jsonData === null) {
            throw new EpointException(3, "Failed to decode JSON data.");
        }

        return $jsonData;
    }

    /**
     * @param $endpoint
     * @param $data
     * @param $responseClass
     * @return APIResponse|mixed
     * @throws EpointException
     */
    private function sendRequest($endpoint, $data, $responseClass): mixed
    {
        $encodedData = $this->encodeData($data);
        $signature = $this->generateSignature($encodedData);

        $postFields = http_build_query([
            'data' => $encodedData,
            'signature' => $signature
        ], '', "&");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $this->handleResponse($response, intval($httpCode), $responseClass);
    }

    // Handle response and return the appropriate response class

    /**
     * @param $response
     * @param int $httpCode
     * @param $responseClass
     * @return mixed
     * @throws EpointException
     */
    private function handleResponse($response, int $httpCode, $responseClass): APIResponse
    {
        if ($this->printResponse) {
            print_r($response);
        }
        $responseBody = json_decode($response, true);
        if (!is_array($responseBody)) {
            throw new EpointException(0, "Wrong response: " . $responseBody);
        }
        return new $responseClass($responseBody, $httpCode);
    }


    /**
     * @param $amount
     * @param $currency
     * @param $orderId
     * @param $description
     * @param $successUrl
     * @param $errorUrl
     * @param $language string Page display language. Possible values: az, en,ru.
     * @return CreatePaymentResponse
     * @throws EpointException
     */
    public function createPayment($amount, $currency, $orderId, $description, $successUrl, $errorUrl, string $language = "az"): CreatePaymentResponse
    {

        $data = [
            'public_key' => $this->publicKey,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $orderId,
            'description' => $description,
            'success_redirect_url' => $successUrl,
            'error_redirect_url' => $errorUrl,
            'language' => $language,
        ];

        return $this->sendRequest('request', $data, CreatePaymentResponse::class);
    }


    /**
     * @param Wallets $walletId
     * @param $amount
     * @param $currency
     * @param $orderId
     * @param $description
     * @param $language string Page display language. Possible values: az, en,ru.
     * @return CreateWalletPaymentResponse
     * @throws EpointException
     */
    public function createWalletPayment(Wallets $walletId, $amount, $currency, $orderId, $description, $language = "az"): CreateWalletPaymentResponse
    {

        $data = [
            'public_key' => $this->publicKey,
            'wallet_id' => $walletId->value,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $orderId,
            'description' => $description,
            'language' => $language,
        ];

        return $this->sendRequest('wallet/payment', $data, CreateWalletPaymentResponse::class);
    }


    /**
     * @throws EpointException
     */
    public function checkPaymentStatus($transactionId): GetPaymentStatusResponse
    {
        $data = [
            'public_key' => $this->publicKey,
            'transaction' => $transactionId
        ];

        return $this->sendRequest('get-status', $data, GetPaymentStatusResponse::class);
    }


    /**
     * Cancel a transaction.
     *
     * @param string $transactionId The Epoint transaction ID to cancel.
     * @param float|null $amount The amount to refund (optional, for partial refunds).
     * @param string $currency The currency code (e.g., "AZN").
     * @return CancelOperationResponse The response from the cancel operation.
     * @throws EpointException If the request fails.
     */
    public function cancelOperation(string $transactionId, ?float $amount = null, string $currency = 'AZN'): CancelOperationResponse
    {
        $data = [
            'public_key' => $this->publicKey,
            'transaction' => $transactionId,
            'currency' => $currency,
        ];

        if ($amount !== null) {
            $data['amount'] = $amount;
        }

        return $this->sendRequest('reverse', $data, CancelOperationResponse::class);
    }


    // Google Pay and Apple Pay Integration

    /**
     * @throws EpointException
     */
    public function createWidgetUri($amount, $orderId, $description): CreateWidgetResponse
    {
        $data = [
            'public_key' => $this->publicKey,
            'amount' => $amount,
            'order_id' => $orderId,
            'description' => $description
        ];

        return $this->sendRequest('token/widget', $data, CreateWidgetResponse::class);
    }

    /**
     * @throws EpointException
     */
    public function getWallets(): GetWalletStatusResponse
    {

        $data = [
            'public_key' => $this->publicKey,
        ];

        return $this->sendRequest('wallet/status', $data, GetWalletStatusResponse::class);
    }


}