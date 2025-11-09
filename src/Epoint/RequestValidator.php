<?php

namespace FaganChalabizada\Epoint;

use FaganChalabizada\Epoint\Exception\EpointException;
use JetBrains\PhpStorm\Pure;

class RequestValidator
{
    private string $privateKey; // Private key provided by Epoint

    public function __construct(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Validate the incoming callback request from Epoint.
     *
     * @param string $data The base64-encoded data from the callback.
     * @param string $receivedSignature The base64-encoded signature sent with the request.
     * @return bool True if the callback is valid, false otherwise.
     */
    #[Pure] public function verifySignature(string $data, string $receivedSignature): bool
    {
        // Step 1: Generate the expected signature
        $expectedSignature = $this->generateSignature($data);

        // Step 2: Compare the expected signature with the received signature
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Generate the signature for the callback data.
     *
     * @param string $data The base64-encoded data.
     * @return string The generated signature (base64-encoded).
     */
    private function generateSignature(string $data): string
    {
        // Concatenate private_key + data + private_key
        $signatureString = $this->privateKey . $data . $this->privateKey;

        // Generate SHA1 hash of the concatenated string
        $sha1Hash = sha1($signatureString, true);

        // Base64 encode the SHA1 hash
        return base64_encode($sha1Hash);
    }
}