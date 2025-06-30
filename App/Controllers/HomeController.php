<?php
// ============================================================================
// File:    HomeController.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Controller.php");

class HomeController extends Controller
{
    public function Index()
    {
        return $this->View();
    }

    public function RedirectTest()
    {
        return $this->Redirect("https://recepseymenkonuk.com");
    }

    public function LocalRedirectTest()
    {
        return $this->LocalRedirect("/Example");
    }

    public function JsonTest()
    {
        return $this->Json([
            "key1" => "value1",
            "key2" => "value2"
        ]);
    }

    public function JsonFailTest()
    {
        return $this->JsonFail("example fail", []);
    }

    public function JsonSuccessTest()
    {
        return $this->JsonSuccess("example success", []);
    }

    public function ContentTest()
    {
        return $this->Content("Hello World!");
    }

    public function FileTest()
    {
        return $this->File(CONTROLLER . "ExampleController.php", "text/html");
    }

    public function StreamTest()
    {
        return $this->StreamFile(CONTROLLER . "ExampleController.php", "text/html");
    }

    public function StatusCodeTest()
    {
        return $this->StatusCode(300, "Hello World!");
    }

    public function EmptyTest()
    {
        return $this->EmptyResult();
    }

    public function UnauthorizedTest()
    {
        return $this->Unauthorized();
    }

    public function BadRequestTest()
    {
        return $this->BadRequest();
    }
}
