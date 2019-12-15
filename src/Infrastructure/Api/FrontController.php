<?php declare(strict_types=1);

namespace App\Infrastructure\Api;


use App\Infrastructure\Api\Exceptions\ApiException;
use App\Infrastructure\Api\Exceptions\BadRequestException;
use App\Infrastructure\Api\Exceptions\NotFoundException;
use Throwable;

class FrontController
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @param array $routing
     * @param RequestHandlerFactory $requestHandlerFactory
     *
     * @return FrontController
     */
    public static function createInstance(array $routing, RequestHandlerFactory $requestHandlerFactory)
    {
        $frontController = new self();
        foreach ($routing as list($method, $path, $handlerClassName)) {
            $frontController->registerHandler(
                $requestHandlerFactory->createRequestHandler($handlerClassName),
                new ApiRequestMatcher($method, $path)
            );
        }

        return $frontController;
    }

    /**
     * @param ApiRequestHandler $requestHandler
     * @param ApiRequestMatcher $requestMatcher
     */
    public function registerHandler(ApiRequestHandler $requestHandler, ApiRequestMatcher $requestMatcher): void
    {
        $this->handlers[] = [
            'handler' => $requestHandler,
            'matcher' => $requestMatcher,
        ];
    }

    /**
     * @param ApiRequest $request
     *
     * @return ApiResponse
     */
    public function handle(ApiRequest $request): ApiResponse
    {
        //RequestMatcher will add path variables into request,
        // clone it here to keep request object isolated from this changes
        $request = clone $request;

        try {
            $response = $this->chooseRequestHandler($request)->handle($request);
        } catch (BadRequestException $x) {
            $response = new ApiResponse($x->getCode(), ['message' => $x->getMessage(), 'errors' => $x->getErrors()]);
        } catch (ApiException $x) {
            $response = new ApiResponse($x->getCode(), ['message' => $x->getMessage()]);
        } catch (Throwable $x) {
            $response = new ApiResponse(
                ApiResponse::STATUS_CODE_SERVER_ERROR,
                [
                    'message' => $x->getMessage(),
                    'trace' => $x->getTrace(),
                ]
            );
        }

        return $response;
    }

    /**
     * @param ApiRequest $request
     *
     * @return ApiRequestHandler
     */
    private function chooseRequestHandler(ApiRequest $request): ApiRequestHandler
    {
        foreach ($this->handlers as $item) {
            /** @var ApiRequestMatcher $matcher */
            $matcher = $item['matcher'];
            if ($matcher->match($request)) {
                return $item['handler'];
            }
        }

        throw new NotFoundException(
            sprintf(
                'Can not find handler for [%s %s]',
                $request->getMethod(),
                $request->getPath()
            )
        );
    }
}
