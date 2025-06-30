<?php
// ============================================================================
// File:    RejectAuthenticatedMiddleware.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Controller.php");

class RejectAuthenticatedMiddleware extends Controller
{
    public function Handle($next)
    {
        return $next();
    }
}
