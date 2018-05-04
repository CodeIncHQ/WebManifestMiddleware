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
namespace CodeInc\WebManifestMiddleware;
use CodeInc\WebManifestMiddleware\Assets\WebManifestResponse;
use CodeInc\WebManifestMiddleware\Exceptions\WebManifestParamValueException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class WebManifestMiddleware.
 *
 * Comment are extracted from the official Mozilla web manifest documentation available here:
 * https://developer.mozilla.org/docs/Web/Manifest
 *
 * @package CodeInc\WebManifestMiddleware
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @license MIT <https://github.com/CodeIncHQ/WebManifestMiddleware/blob/master/LICENSE>
 * @link https://github.com/CodeIncHQ/WebManifestMiddleware
 */
class WebManifestMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $uriPath;

    /**
     * @var array
     */
    private $webManifest = [];

    /**
     * WebManifestMiddleware constructor.
     *
     * @param string $uriPath
     */
    public function __construct(string $uriPath = '/manifest.webmanifest')
    {
        $this->uriPath = $uriPath;
    }

    /**
     * Sets the 'background_color' member. Defines the expected background color for the web application.
     * This value repeats what is already available in the application stylesheet, but can be used by browsers
     * to draw the background color of a web application when the manifest is available before the style sheet
     * has loaded. This creates a smooth transition between launching the web application and loading the
     * application's content.
     *
     * Note: The background_color member is only meant to improve the user experience while a web application
     * is loading and must not be used by the user agent as the background color when the web application's
     * stylesheet is available.
     *
     * @param string $value
     */
    public function setBackgroundColor(string $value):void
    {
        $this->webManifest['background_color'] = $value;
    }

    /**
     * Sets the 'description' member. Provides a general description of what the web application does.
     *
     * @param string $value
     */
    public function setDescription(string $value):void
    {
        $this->webManifest['description'] = $value;
    }

    /**
     * Sets the 'dir' member. Specifies the primary text direction for the 'name', 'short_name',
     * and 'description' members. Together with the lang member, it can help provide the correct
     * display of right-to-left languages.
     *
     * Note: When the value is omitted, it defaults to auto.
     *
     * Possible values are:
     * --------------------
     * - 'ltr'  -> Left to right
     * - 'rtl'  -> Right to left
     * - 'auto'
     *
     * @param string $value
     * @throws WebManifestParamValueException
     */
    public function setDirection(string $value):void
    {
        $possibleValues = ['ltr', 'rtl'];
        if (!in_array($value, $possibleValues)) {
            throw new WebManifestParamValueException('dir', $value, $possibleValues, $this);
        }
        $this->webManifest['dir'] = $value;
    }

    /**
     * Sets the 'display' member. Defines the developer's preferred display mode for the web application.
     *
     * Possible values are:
     * --------------------
     * - 'fullscreen' -> All of the available display area is used and no user agent chrome is shown.
     * - 'standalone' -> The application will look and feel like a standalone application. This can include
     *                   the application having a different window, its own icon in the application launcher, etc.
     *                   In this mode, the user agent will exclude UI elements for controlling navigation, but
     *                   can include other UI elements such as a status bar.
     * - 'minimal-ui' -> The application will look and feel like a standalone application, but will have a minimal
     *                   set of UI elements for controlling navigation. The elements will vary by browser.
     * - 'browser'    -> The application opens in a conventional browser tab or new window, depending on the
     *                   browser and platform. This is the default.
     *
     * Note: You can selectively apply CSS to your app based on the display mode, using the display-mode
     * media feature. This can be used to provide a consistent user experience between launching a site
     * from an URL and launching it from a desktop icon.
     *
     * @param string $value
     * @throws WebManifestParamValueException
     */
    public function setDisplay(string $value):void
    {
        $possibleValues = ['fullscreen', 'standalone', 'minimal-ui', 'browser'];
        if (!in_array($value, $possibleValues)) {
            throw new WebManifestParamValueException('display', $value, $possibleValues, $this);
        }
        $this->webManifest['display'] = $value;
    }

    /**
     * Adds an icon ('icons' member). Specifies an array of image objects that can serve as application icons
     * in various contexts. For example, they can be used to represent the web application amongst a list of
     * other applications, or to integrate the web application with an OS's task switcher and/or system
     * preferences.
     *
     * @param string      $src   The path to the image file. If src is a relative URL, the base URL will be
     *                           the URL of the manifest.
     * @param null|string $sizes A string containing space-separated image dimensions.
     * @param null|string $type  A hint as to the media type of the image.The purpose of this member is to allow a user
     *                           agent to quickly ignore images of media types it does not support.
     * @param int|null    $density
     */
    public function addIcon(string $src, ?string $sizes = null, ?string $type = null, ?int $density = null):void
    {
        $icon = ['src' => $src];
        if ($sizes) {
            $icon['sizes'] = $sizes;
        }
        if ($type) {
            $icon['type'] = $type;
        }
        if ($density) {
            $icon['density'] = $density;
        }
        $this->webManifest['icons'][] = $icon;
    }

    /**
     * Sets the 'lang' member. Specifies the primary language for the values in the 'name' and 'short_name' members.
     * This value is a string containing a single language tag.
     *
     * @param string $value
     */
    public function setLang(string $value):void
    {
        $this->webManifest['lang'] = $value;
    }

    /**
     * Sets the 'name' member. Provides a human-readable name for the application as it is intended to be displayed to
     * the user, for example among a list of other applications or as a label for an icon.
     *
     * @param string $value
     */
    public function setName(string $value):void
    {
        $this->webManifest['name'] = $value;
    }

    /**
     * Sets the 'orientation' member. Defines the default orientation for all the web application's top level browsing
     * contexts.
     *
     * Possible values are:
     * --------------------
     * - 'any'
     * - 'natural'
     * - 'landscape'
     * - 'landscape-primary'
     * - 'landscape-secondary'
     * - 'portrait'
     * - 'portrait-primary'
     * - 'portrait-secondary'
     *
     * @param string $value
     * @throws WebManifestParamValueException
     */
    public function setOrientation(string $value):void
    {
        $possibleValues = ['any', 'natural', 'landscape', 'landscape-primary', 'landscape-secondary', 'portrait',
            'portrait-primary', 'portrait-secondary'];
        if (!in_array($value, $possibleValues)) {
            throw new WebManifestParamValueException('orientation', $value, $possibleValues, $this);
        }
        $this->webManifest['orientation'] = $value;
    }

    /**
     * Sets the 'prefer_related_applications' member. Specifies a boolean value that hints for the user agent to
     * indicate to the user that the specified related applications (see below) are available, and recommended over the
     * web application. This should only be used if the related native apps really do offer something that the web
     * application can't do.
     *
     * Note: If omitted, the value defaults to false.
     *
     * @param string      $platform The platform on which the application can be found.
     * @param string      $url      The URL at which the application can be found.
     * @param null|string $id       The ID used to represent the application on the specified platform.
     */
    public function setPreferRelatedApplications(string $platform, string $url, ?string $id = null):void
    {
        $application = [
            'platform' => $platform,
            'url' => $url
        ];
        if ($id) {
            $application['id'] = $id;
        }
        $this->webManifest['prefer_related_applications'][] = $application;
    }

    /**
     * Sets the 'scope' member. Defines the navigation scope of this web application's application context. This
     * basically restricts what web pages can be viewed while the manifest is applied. If the user navigates the
     * application outside the scope, it returns to being a normal web page.
     *
     * If the scope is a relative URL, the base URL will be the URL of the manifest.
     *
     * @param string $value
     */
    public function setScope(string $value):void
    {
        $this->webManifest['scope'] = $value;
    }

    /**
     * Sets the 'short_name' member. Provides a short human-readable name for the application. This is intended for use
     * where there is insufficient space to display the full name of the web application.
     *
     * @param string $value
     */
    public function setShortName(string $value):void
    {
        $this->webManifest['short_name'] = $value;
    }

    /**
     * Sets the 'start_url' member. Specifies the URL that loads when a user launches the application from a device
     * (e.g. when added to home screen), typically the index file. Note that this has to be a relative URL pointing to
     * the index file, relative to the site origin.
     *
     * @param string $value
     */
    public function setStartUrl(string $value):void
    {
        $this->webManifest['start_url'] = $value;
    }

    /**
     * Sets the 'theme_color' member. Defines the default theme color for an application. This sometimes affects how
     * the application is displayed by the OS (e.g., on Android's task switcher, the theme color surrounds the
     * application).
     *
     * @param string $value
     */
    public function setThemeColor(string $value):void
    {
        $this->webManifest['theme_color'] = $value;
    }

    /**
     * Returns the generated web manifest.
     *
     * @return array
     */
    public function getWebManifest():array
    {
        return $this->webManifest;
    }

    /**
     * Returns the URI path.
     *
     * @return string
     */
    public function getUriPath():string
    {
        return $this->uriPath;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($this->isWebManifestRequest($request)) {
            return new WebManifestResponse($this->webManifest);
        }
        return $handler->handle($request);
    }

    /**
     * Verifies if the request correspnd to the web manifest.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function isWebManifestRequest(ServerRequestInterface $request):bool
    {
        return $request->getUri()->getPath() == $this->uriPath;
    }
}