<?php
// ============================================================================
// File:    Index.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(__DIR__ . DIRECTORY_SEPARATOR . "Globals.init.php");

require_once(ROUTE . "RouteConfig.php");
require_once(CORE . "Router.php");

$ViewData = [];
ob_start();

RouteConfig::register();
echo Router::dispatch();
