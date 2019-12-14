<?php declare(strict_types=1);

namespace App\Infrastructure\Api;

class ApiRequest
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param string $method
     * @param string $path
     * @param array $arguments
     */
    public function __construct(string $method, string $path, array $arguments = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     *
     * @return ApiRequest
     */
    public function addArguments(array $arguments): self
    {
        $this->arguments = array_replace_recursive($this->arguments, $arguments);

        return $this;
    }
}
