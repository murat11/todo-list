<?php declare(strict_types=1);

namespace App\Infrastructure\Uri;

class UrlBuilder
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $path
     * @param array $parameters
     *
     * @return string
     */
    public function buildUrl(string $path, array $parameters = null): string
    {
        $url = trim($this->baseUrl, '/').'/'.trim($path, '/');
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        return $url;
    }
}
