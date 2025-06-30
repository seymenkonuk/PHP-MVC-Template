<?php
// ============================================================================
// File:    ExampleRepository.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Repository.php");

require_once(MODEL . "ExampleModel.php");

class ExampleRepository extends Repository
{
    public function saveData(Example $data)
    {
        return true;
    }
}
