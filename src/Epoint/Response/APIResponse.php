<?php

namespace FaganChalabizada\Epoint\Response;

use FaganChalabizada\Epoint\Exception\EpointException;

class APIResponse
{
    protected array $data;
    protected int $httpCode;

    /**
     * @param array $responseData The decoded JSON response from the API.
     * @param int $httpCode The HTTP status code of the response.
     * @throws EpointException If the response indicates an error.
     */
    public function __construct(array $responseData, int $httpCode)
    {
        $this->data = $responseData;
        $this->httpCode = $httpCode;

        // Check if the response indicates an error
        if (!$this->isSuccess()) {
            $errorCode = $this->data['code'] ?? 'UNKNOWN_ERROR';
            $errorMessage = $this->data['message'] ?? 'An unknown error occurred';
            throw new EpointException($errorCode, $errorMessage);
        }
    }

    /**
     * Check if the API request was successful.
     *
     * @return bool True if the status is "success", false otherwise.
     */
    public function isSuccess(): bool
    {
        return ($this->data['status'] ?? 'error') === 'success';
    }

    /**
     * Get the error message from the response.
     *
     * @return string|null The error message, or null if no error occurred.
     */
    public function getErrorMessage(): ?string
    {
        return $this->data['message'] ?? null;
    }

    /**
     * Get the raw response data.
     *
     * @return array The raw response data.
     */
    public function getRawData(): array
    {
        return $this->data;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
