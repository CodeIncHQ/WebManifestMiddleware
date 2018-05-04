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
// Time:     17:18
// Project:  WebManifestMiddleware
//
declare(strict_types=1);
namespace CodeInc\WebManifestMiddleware\Assets;
use CodeInc\Psr7Responses\JsonResponse;


/**
 * Class WebManifestResponse
 *
 * @package Assets
 * @author  Joan Fabrégat <joan@codeinc.fr>
 */
class WebManifestResponse extends JsonResponse
{
    public const DEFAULT_CHARSET = "utf-8";

    /**
     * WebManifestResponse constructor.
     *
     * @param array       $webManifest
     * @param null|string $charset
     * @param int         $status
     * @param array       $headers
     * @param string      $version
     * @param null|string $reason
     */
    public function __construct(array $webManifest, ?string $charset = null, int $status = 200, array $headers = [],
        string $version = '1.1', ?string $reason = null)
    {
        $headers['Content-Type'] = 'application/manifest+json; charset='.($charset ?? self::DEFAULT_CHARSET);
        parent::__construct(
            json_encode($webManifest, JSON_PRETTY_PRINT),
            $charset,
            $status,
            $headers,
            $version,
            $reason
        );
    }
}