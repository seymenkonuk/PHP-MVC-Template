<?php
// ============================================================================
// File:    RouteConfig.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Router.php");
require_once(ROUTE . "ExampleRoute.php");

class RouteConfig
{
    public static function register()
    {
        ExampleRoute::register();

        // Default Route
        Router::addRoute(
            method: Router::ALL_METHOD,
            url: "",
            controller: "Home",
            action: "Index"
        );
        Router::addRoute(
            method: Router::ALL_METHOD,
            url: "{controller}",
            action: "Index"
        );
        Router::addRoute(
            method: Router::ALL_METHOD,
            url: "{controller}/{action}",
        );
    }
}
