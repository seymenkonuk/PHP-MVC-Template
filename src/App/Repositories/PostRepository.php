<?php
// ============================================================================
// File:    PostRepository.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Repository.php");

require_once(MODEL . "PostModel.php");

class PostRepository extends Repository
{
    public function example()
    {
        return true;
    }
}
