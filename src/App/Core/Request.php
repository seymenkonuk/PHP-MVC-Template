<?php
// ============================================================================
// File:    Request.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Router.php");

class Request
{
    public static function getMethod()
    {
        return mb_strtoupper($_SERVER['REQUEST_METHOD'], "UTF-8");
    }

    public static function getEndpoint()
    {
        // Domain name is example
        $result = parse_url("https://recepseymenkonuk.com" . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $result;
    }

    public static function getHeaders()
    {
        return getallheaders();
    }

    public static function getCookies()
    {
        return $_COOKIE;
    }

    public static function getUserIP()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip_address = trim($ip_list[0]);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = null;
        }

        return filter_var($ip_address, FILTER_VALIDATE_IP) ? $ip_address : NULL;
    }

    public static function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? "";
    }

    public static function getBody()
    {
        return file_get_contents("php://input");
    }

    public static function getJsonBody()
    {
        return json_decode(self::getBody(), true);
    }

    public static function getFormBody()
    {
        parse_str(self::getBody(), $result);
        return $result;
    }

    public static function getQuery()
    {
        return $_GET;
    }

    public static function getParams()
    {
        return Router::getControllerAction()["parameters"];
    }

    public static function getFiles()
    {
        return $_FILES;
    }
}
