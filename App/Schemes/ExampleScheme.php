<?php
// ============================================================================
// File:    ExampleScheme.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Validator.php");

class ExampleScheme
{
    public static function test()
    {
        return [
            "field1" => Validator::create()->int()->min(10)->max(30)->require(),
            "field2" => Validator::create()->array(10, 20)->str()->min(11)->max(11)->optional(),
            "field3" => [
                "field4" => Validator::create()->email()->require(),
            ],
        ];
    }
}
