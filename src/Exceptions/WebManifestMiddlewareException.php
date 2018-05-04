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
// Time:     16:45
// Project:  WebManifestMiddleware
//
declare(strict_types=1);
namespace CodeInc\WebManifestMiddleware\Exceptions;
use CodeInc\WebManifestMiddleware\WebManifestMiddleware;
use Throwable;


/**
 * Class WebManifestMiddlewareException
 *
 * @package CodeInc\WebManifestMiddleware\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class WebManifestMiddlewareException extends \Exception
{
    /**
     * @var WebManifestMiddleware
     */
    private $webManifestMiddleware;

    /**
     * WebManifestMiddlewareException constructor.
     *
     * @param string $message
     * @param WebManifestMiddleware $webManifestMiddleware
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, WebManifestMiddleware $webManifestMiddleware,
        int $code = 0, Throwable $previous = null)
    {
        $this->webManifestMiddleware = $webManifestMiddleware;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return WebManifestMiddleware
     */
    public function getWebManifestMiddleware():WebManifestMiddleware
    {
        return $this->webManifestMiddleware;
    }
}