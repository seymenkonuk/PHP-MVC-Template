<?php
// ============================================================================
// File:    UserRoute.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Router.php");

class UserRoute
{
    public static function register()
    {
        Router::addRoute(
            method: Router::GET_METHOD,
            url: "Example/Index",
            controller: "Example",
            action: "Index"
        );
        Router::addRoute(
            method: Router::GET_METHOD,
            url: "Example/Data/{id}",
            controller: "Example",
            action: "Data"
        );
        Router::addRoute(
            method: Router::GET_METHOD,
            url: "Example/Foo/{id}/Bar/{code}",
            controller: "Example",
            action: "Foobar"
        );
    }
}
