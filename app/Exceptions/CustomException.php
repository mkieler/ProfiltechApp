<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected string $title;
    protected string $errorCode;
    protected int $statusCode;

    public function __construct(string $title, string $message, string $errorCode, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->title = $title;
        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
