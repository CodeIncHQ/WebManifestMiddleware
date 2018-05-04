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
// Time:     16:57
// Project:  WebManifestMiddleware
//
declare(strict_types=1);
namespace CodeInc\WebManifestMiddleware\Exceptions;
use CodeInc\WebManifestMiddleware\WebManifestMiddleware;
use Throwable;


/**
 * Class WebManifestParamValueException
 *
 * @package CodeInc\WebManifestMiddleware\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class WebManifestParamValueException extends WebManifestMiddlewareException
{
    /**
     * ParamValueException constructor.
     *
     * @param string $paramName
     * @param string $value
     * @param array $possibleValues
     * @param WebManifestMiddleware $webManifestMiddleware
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(string $paramName, string $value, array $possibleValues,
        WebManifestMiddleware $webManifestMiddleware, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Wrong value %s for the parameter %s. Possibles valus are: %s',
                $value, $paramName, implode(', ', $possibleValues)),
            $webManifestMiddleware,
            $code,
            $previous
        );
    }
}