<?php

namespace Test\Unit\Infrastructure\Api;

use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestMatcher;
use PHPUnit\Framework\TestCase;

class ApiRequestMatcherTest extends TestCase
{
    public function testSimpleMatchOk()
    {
        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);
        $request->method('getPath')->willReturn('/');

        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, '/'))->match($request));
    }

    public function testMatchPathTrailingSlashOk()
    {
        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);
        $request->method('getPath')->willReturn('aaa');

        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, '/aaa'))->match($request));
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'aaa/'))->match($request));
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'aaa'))->match($request));

        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);
        $request->method('getPath')->willReturn('bbb/');
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, '/bbb'))->match($request));
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'bbb/'))->match($request));
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'bbb'))->match($request));

        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);
        $request->method('getPath')->willReturn('ccc');
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, '/ccc'))->match($request));
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'ccc/'))->match($request));
        $this->assertTrue((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'ccc'))->match($request));
    }

    public function testMatchMethodFails()
    {
        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);

        $this->assertFalse((new ApiRequestMatcher(ApiRequest::METHOD_POST, '/'))->match($request));
    }

    public function testMatchPathFails()
    {
        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);
        $request->method('getPath')->willReturn('/aaa');

        $this->assertFalse((new ApiRequestMatcher(ApiRequest::METHOD_GET, '/'))->match($request));

        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_GET);
        $request->method('getPath')->willReturn('/');

        $this->assertFalse((new ApiRequestMatcher(ApiRequest::METHOD_GET, 'aaa'))->match($request));
    }

    public function testMatchWithPathVariablesOk()
    {
        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_POST);
        $request->method('getPath')->willReturn('api.php/lists/aaa-bbb/items/ccc_ddd');
        $request->expects($this->once())->method('addArguments')
            ->with(['list-id' => 'aaa-bbb', 'item-id' => 'ccc_ddd']);

        $apiRequestMatcher = new ApiRequestMatcher(ApiRequest::METHOD_POST, 'api.php/lists/:list-id/items/:item-id');
        $apiRequestMatcher->match($request);
    }

    public function testMatchWithPathVariablesFailed()
    {
        $request = $this->createMock(ApiRequest::class);
        $request->method('getMethod')->willReturn(ApiRequest::METHOD_POST);
        $request->method('getPath')->willReturn('api.php/lists/aaa-bbb/items/');

        $apiRequestMatcher = new ApiRequestMatcher(ApiRequest::METHOD_POST, 'api.php/lists/:list-id/items/:item-id');
        $this->assertFalse($apiRequestMatcher->match($request));
    }
}
