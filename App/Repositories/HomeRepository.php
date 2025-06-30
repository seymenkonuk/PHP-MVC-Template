<?php
// ============================================================================
// File:    HomeRepository.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Repository.php");

class HomeRepository extends Repository
{
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM home");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
