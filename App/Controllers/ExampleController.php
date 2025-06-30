<?php
// ============================================================================
// File:    ExampleController.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Controller.php");

class ExampleController extends Controller
{
    public function Index()
    {
        global $ViewData;

        $ViewData["title"] = "Example";
        return $this->View();
    }
}
