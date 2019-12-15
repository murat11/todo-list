<?php declare(strict_types=1);

namespace App\Infrastructure\Api;

class ApiResponse
{
    const STATUS_CODE_OK = 200;
    const STATUS_CODE_CREATED = 201;
    const STATUS_CODE_BAD_REQUEST = 400;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_CONFLICT = 409;
    const STATUS_CODE_SERVER_ERROR = 500;

    const REASONS = [
        self::STATUS_CODE_OK => 'OK',
        self::STATUS_CODE_CREATED => 'Created',
        self::STATUS_CODE_BAD_REQUEST => 'Bad Request',
        self::STATUS_CODE_NOT_FOUND => 'Not Found',
        self::STATUS_CODE_CONFLICT => 'Conflict',
        self::STATUS_CODE_SERVER_ERROR => 'Internal Server Error',
    ];

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $body;

    public function __construct(int $statusCode, array $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    /**
     * @param int $statusCode
     *
     * @return ApiResponse
     */
    public function setStatusCode(int $statusCode): ApiResponse
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param array $body
     *
     * @return ApiResponse
     */
    public function setBody(array $body): ApiResponse
    {
        $this->body = $body;

        return $this;
    }

    public function render()
    {
        header(sprintf('HTTP/1.1 %d %s', $this->statusCode,self::REASONS[$this->statusCode] ?? ''));
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($this->body, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
