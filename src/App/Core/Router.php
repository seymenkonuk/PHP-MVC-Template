<?php
// ============================================================================
// File:    Router.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Request.php");

class Router
{
    public const GET_METHOD    = 0b1;
    public const POST_METHOD   = 0b10;
    public const PUT_METHOD    = 0b100;
    public const PATCH_METHOD  = 0b1000;
    public const DELETE_METHOD = 0b10000;
    public const ALL_METHOD = Router::GET_METHOD | Router::POST_METHOD | Router::PUT_METHOD | Router::PATCH_METHOD | Router::DELETE_METHOD;

    public const METHOD_DICTIONARY = [
        "GET" => self::GET_METHOD,
        "POST" => self::POST_METHOD,
        "PUT" => self::PUT_METHOD,
        "PATCH" => self::PATCH_METHOD,
        "DELETE" => self::DELETE_METHOD,
    ];

    private static array $routes = [];
    private static array $middlewares = [];

    private static bool $is_resolve = false;

    private static string $controller = "Home";
    private static string $action = "Index";
    private static array $parameters = [];

    public static function addMiddleware($middleware)
    {
        self::$middlewares[] = [$middleware, "Handle"];
    }

    public static function addRoute(int $method, string $url, string $controller = "", string $action = "", array $placeholders = [], array $middlewares = [])
    {
        self::$routes[] = [
            "url" => "/" . $url,
            "method" => $method,
            "controller" => $controller,
            "action" => $action,
            "placeholders" => $placeholders,
            "middlewares" => array_map(function ($middleware) {
                return [$middleware, "Handle"];
            }, $middlewares),
        ];
    }

    private static function convertRouteToRegex($route, $placeholders)
    {
        $index = 0;
        $controller_index = -1;
        $action_index = -1;

        $route_regex = preg_replace_callback("/\\\\\{(\w+)\\\\\}/", function ($matches) use ($placeholders, &$index, &$controller_index, &$action_index) {
            // Özel Alanlar ise İndex'lerini Not Al
            if ($matches[1] == "controller") {
                $controller_index = $index;
            } else if ($matches[1] == "action") {
                $action_index = $index;
            }

            // O Alanın Regex'i Tanımlanmadıysa Varsayılan Regex'i Ata
            if (!isset($placeholders[$matches[1]])) {
                $placeholders[$matches[1]] = "\w+";
            }

            // İndex'i Arttır
            $index += 1;

            // Alanı Regex ile Değiştir
            return "(" . $placeholders[$matches[1]] . ")";
        }, preg_quote($route, "/"));

        // Sonucu Döndür
        return [
            "route_regex" => $route_regex,
            "controller_index" => $controller_index,
            "action_index" => $action_index
        ];
    }

    private static function resolveControllerAction()
    {
        // Daha Önceden Hesaplandıysa Bir Daha Hesaplama
        if (self::$is_resolve) {
            return;
        }

        // İstek Endpointi ve Metodu
        $endpoint = Request::getEndpoint();
        $method = self::METHOD_DICTIONARY[Request::getMethod()];

        // Route'ların Hepsini Sırayla Kontrol Et
        foreach (self::$routes as $route) {
            // Bu Route Olamaz (Metot Uymuyor)
            if (($route["method"] & $method) != $method) {
                continue;
            }

            // Route'u Regex'e Dönüştür
            ["route_regex" => $route_regex, "controller_index" => $controller_index, "action_index" => $action_index]
                = self::convertRouteToRegex($route["url"], $route["placeholders"]);

            // Bu Route Olamaz (Endpoint Uymuyor)
            if (!preg_match_all("/^" . $route_regex . "$/", $endpoint, $matches)) {
                continue;
            }

            unset($matches[0]);
            $matches = array_values($matches);

            // Route Eşleşti
            self::$is_resolve = true;
            self::$controller = ($controller_index == -1) ? $route["controller"] : $matches[$controller_index][0];
            self::$action = ($action_index == -1) ? $route["action"] : $matches[$action_index][0];
            self::$middlewares = array_merge(self::$middlewares, $route["middlewares"]);

            unset($matches[$controller_index]);
            unset($matches[$action_index]);
            $matches = array_values($matches);

            self::$parameters = array_map(function ($item) {
                return $item[0];
            }, $matches);

            return;
        }
    }

    public static function getControllerAction()
    {
        // Resolve URI
        if (!self::$is_resolve) {
            self::resolveControllerAction();
        }

        // Resolve Error
        if (!self::$is_resolve) {
            return [];
        }

        // Return Result
        return [
            "controller" => self::$controller,
            "action" => self::$action,
            "parameters" => self::$parameters,
        ];
    }

    public static function dispatch()
    {
        // Resolve URI
        if (!self::$is_resolve) {
            self::resolveControllerAction();
        }

        // Route Error
        if (!self::$is_resolve) {
            http_response_code(404);
            return;
        }

        // Controller Not Found
        $controller = self::$controller . "Controller";
        if (!is_file(CONTROLLER . $controller . ".php")) {
            http_response_code(404);
            return;
        }

        // Include Controller
        require_once(CONTROLLER . $controller . ".php");
        $controller = new $controller;

        // Action Not Found
        if (!method_exists($controller, self::$action)) {
            http_response_code(404);
            return;
        }

        // Asıl Çalıştırılacak Action 
        $currentFunction = function () use ($controller) {
            return call_user_func_array([$controller, self::$action], self::$parameters);
        };

        // Ondan Öncesine Middleware Fonksiyonlarını Ekle
        for ($i = count(self::$middlewares) - 1; $i >= 0; $i--) {
            $currentFunction = function () use ($i, $currentFunction) {
                return self::$middlewares[$i]($currentFunction);
            };
        }

        // Fonksiyonları Çalıştırmaya Başla
        return $currentFunction();
    }
}
