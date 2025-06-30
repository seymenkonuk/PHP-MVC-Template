<?php
// ============================================================================
// File:    LoginScheme.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Validator.php");

require_once(SCHEME . "regex.php");

class LoginScheme
{
    public static function Body()
    {
        return [
            "username" => Validator::create()->email()->require(),
            "password" => Validator::create()->str()->min(PASSWORD_MIN_LEN)->max(PASSWORD_MAX_LEN)->insertRegexRule(PASSWORD_REGEX_ERROR, PASSWORD_REGEX_RULE)->require(),
        ];
    }

    public static function Query()
    {
        return [];
    }

    public static function Params()
    {
        return [];
    }
}
