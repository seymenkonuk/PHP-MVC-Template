<?php
// ============================================================================
// File:    AuthRoute.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Router.php");

require_once(MIDDLEWARE . "RejectAuthenticatedMiddleware.php");
require_once(MIDDLEWARE . "RequireAuthMiddleware.php");

class AuthRoute
{
    public static function register()
    {
        // Register
        Router::addRoute(
            method: Router::GET_METHOD,
            url: "auth/register",
            controller: "Auth",
            action: "RegisterForm",
            // middlewares: [new RejectAuthenticatedMiddleware()],
        );
        Router::addRoute(
            method: Router::POST_METHOD,
            url: "auth/register",
            controller: "Auth",
            action: "Register",
            // middlewares: [new RejectAuthenticatedMiddleware()],
        );
        // Login
        Router::addRoute(
            method: Router::GET_METHOD,
            url: "auth/login",
            controller: "Auth",
            action: "LoginForm",
            // middlewares: [new RejectAuthenticatedMiddleware()],
        );
        Router::addRoute(
            method: Router::POST_METHOD,
            url: "auth/login",
            controller: "Auth",
            action: "Login",
            // middlewares: [new RejectAuthenticatedMiddleware()],
        );
        // Me Details
        Router::addRoute(
            method: Router::GET_METHOD,
            url: "auth/me",
            controller: "Auth",
            action: "Me",
            // middlewares: [new RequireAuthMiddleware()],
        );
        // Logout
        Router::addRoute(
            method: Router::POST_METHOD,
            url: "auth/logout",
            controller: "Auth",
            action: "Logout",
            // middlewares: [new RequireAuthMiddleware()],
        );
    }
}
