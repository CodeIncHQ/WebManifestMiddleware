<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     04/05/2018
// Time:     17:23
// Project:  WebManifestMiddleware
//
declare(strict_types=1);
namespace CodeInc\WebManifestMiddleware\Tests;
use CodeInc\MiddlewareTestKit\FakeRequestHandler;
use CodeInc\MiddlewareTestKit\FakeServerRequest;
use CodeInc\WebManifestMiddleware\Assets\WebManifestResponse;
use CodeInc\WebManifestMiddleware\WebManifestMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;


/**
 * Class WebManifestMiddlewareTest
 *
 * @uses WebManifestMiddleware
 * @package CodeInc\WebManifestMiddleware\Tests
 * @author  Joan Fabrégat <joan@codeinc.fr>
 */
final class WebManifestMiddlewareTest extends TestCase
{
    /**
     * @throws \CodeInc\WebManifestMiddleware\Exceptions\WebManifestParamValueException
     */
    public function testWebManifest():void
    {
        $middleware = new WebManifestMiddleware('/manifest.webmanifest');
        $middleware->setName('Test');
        $middleware->setLang('en');
        $middleware->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $middleware->setBackgroundColor('white');
        $middleware->setDisplay('standalone');
        $middleware->addIcon('/a/fake/icon.png', '48x48');
        $webManifest = $middleware->getWebManifest();

        $request = FakeServerRequest::getSecureServerRequestWithPath('/manifest.webmanifest');
        self::assertTrue($middleware->isWebManifestRequest($request));
        $response = $middleware->process(
            $request,
            new FakeRequestHandler()
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInstanceOf(WebManifestResponse::class, $response);

        $responseBody = $response->getBody()->__toString();
        self::assertJson($responseBody);
        self::assertNotFalse($webManifestJson = json_encode($webManifest, JSON_PRETTY_PRINT));
        self::assertJsonStringEqualsJsonString($responseBody, $webManifestJson);
        self::assertNotFalse($responseBody = json_decode($responseBody, true));
        self::assertNotNull($responseBody);
        self::assertEquals($responseBody, $webManifest);
    }

    public function testRegularRequest():void
    {
        $middleware = new WebManifestMiddleware('/manifest.webmanifest');

        $request = FakeServerRequest::getSecureServerRequestWithPath('/a-page.html');
        self::assertFalse($middleware->isWebManifestRequest($request));
        $response = $middleware->process(
            $request,
            new FakeRequestHandler()
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertNotInstanceOf(WebManifestResponse::class, $response);
    }

    public function testHtmlMetaTag():void
    {
        $middleware = new WebManifestMiddleware('/manifest.webmanifest');
        self::assertEquals($middleware->getHtmlMetaTag(), '<link rel="manifest" href="/manifest.webmanifest">');
    }

    /**
     * @expectedException \CodeInc\WebManifestMiddleware\Exceptions\WebManifestParamValueException
     */
    public function testDirectionWrongValue():void
    {
        $middleware = new WebManifestMiddleware();
        $middleware->setDirection('wrong-value');
    }

    /**
     * @expectedException \CodeInc\WebManifestMiddleware\Exceptions\WebManifestParamValueException
     */
    public function testDisplayWrongValue():void
    {
        $middleware = new WebManifestMiddleware();
        $middleware->setDisplay('wrong-value');
    }

    /**
     * @expectedException \CodeInc\WebManifestMiddleware\Exceptions\WebManifestParamValueException
     */
    public function testOrientationWrongValue():void
    {
        $middleware = new WebManifestMiddleware();
        $middleware->setOrientation('wrong-value');
    }
}