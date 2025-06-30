<?php
// ============================================================================
// File:    RouteConfig.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Router.php");

require_once(ROUTE . "AuthRoute.php");
require_once(ROUTE . "PostRoute.php");
require_once(ROUTE . "UserRoute.php");

require_once(MIDDLEWARE . "RequestValidationMiddleware.php");

class RouteConfig
{
    public static function register()
    {
        // Ortak Middleware'ler
        Router::addMiddleware(new RequestValidationMiddleware());

        // Route'lar
        AuthRoute::register();
        PostRoute::register();
        UserRoute::register();

        // Default Route
        Router::addRoute(
            method: Router::ALL_METHOD,
            url: "{controller}/{action}"
        );
    }
}
