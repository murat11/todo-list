<?php declare(strict_types=1);

namespace App\Infrastructure\Api;

class ApiRequestMatcher
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var array
     */
    private $pathVariableNames;

    /**
     * @param string $method
     * @param string $pathTemplate
     */
    public function __construct(string $method, string $pathTemplate)
    {
        $this->method = $method;
        $this->parsePathTemplate($pathTemplate);
    }

    /**
     * @param ApiRequest $request
     *
     * @return bool
     */
    public function match(ApiRequest $request): bool
    {
        if (0 !== strcasecmp($request->getMethod(), $this->method)) {
            return false;
        }

        $requestPath = '/' . trim($request->getPath(), '/');

        if (!preg_match("/^{$this->regex}$/", $requestPath, $realPathMatches)) {
            return false;
        }

        if (!empty($this->pathVariableNames) && sizeof($realPathMatches) > 1) {
            $request->addArguments(
                array_combine(
                    $this->pathVariableNames,
                    array_slice($realPathMatches, 1)
                )
            );
        }

        return true;
    }

    /**
     * @param string $pathTemplate
     */
    private function parsePathTemplate(string $pathTemplate)
    {
        $pathTemplate = '/' . trim($pathTemplate, '/');
        $this->regex = preg_replace('/:[\w\-]+/', '([\\w\\-]+)', $pathTemplate);
        $this->regex = str_replace('/', '\/', $this->regex);

        if (preg_match_all('/:([\w\-]+)/',  $pathTemplate, $pathTemplateMatches)) {
            $this->pathVariableNames = $pathTemplateMatches[1];
        }
    }
}
