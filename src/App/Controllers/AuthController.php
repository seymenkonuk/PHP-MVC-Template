<?php
// ============================================================================
// File:    AuthController.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Controller.php");

class AuthController extends Controller
{
    public function RegisterForm()
    {
        return $this->Content("deneme2", "text/html");
        // return $this->View();
    }

    public function Register()
    {
        return $this->LocalRedirect("/auth/login");
    }

    public function LoginForm()
    {
        return $this->Content("deneme2", "text/html");
        // return $this->View();
    }

    public function Login()
    {
        return $this->View();
    }

    public function Me()
    {
        return $this->View();
    }

    public function Logout()
    {
        return $this->View();
    }
}
