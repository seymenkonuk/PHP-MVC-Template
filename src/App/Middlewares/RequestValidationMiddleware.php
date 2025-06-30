<?php
// ============================================================================
// File:    RequestValidationMiddleware.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Controller.php");
require_once(CORE . "Request.php");
require_once(CORE . "Router.php");
require_once(CORE . "Validator.php");

class RequestValidationMiddleware extends Controller
{
    public function Handle($next)
    {
        $request = $this->getRequest();
        $scheme = $this->getScheme();
        $error = Validator::validateData($scheme, $request);
        if ($error) {
            return $this->JsonFail("Lütfen bilgileri eksiksiz ve doğru şekilde girin!", $error);
        }
        return $next();
    }

    private function getRequest()
    {
        $body = Request::getFormBody();
        $query = Request::getQuery();
        $params = Request::getParams();

        return [
            "body" => $body,
            "query" => $query,
            "params" => $params
        ];
    }

    private function getScheme()
    {
        ["controller" => $controller, "action" => $action] = Router::getControllerAction();

        // Scheme Not Found
        $scheme_name = $action . "Scheme";
        $scheme_path = SCHEME . $controller . DIRECTORY_SEPARATOR . $scheme_name . ".php";
        if (!is_file($scheme_path)) {
            http_response_code(404);
            exit;
        }

        // Include Scheme
        require_once($scheme_path);
        $scheme = new $scheme_name;

        // Body Scheme Not Found
        if (!method_exists($scheme, "Body")) {
            http_response_code(404);
            exit;
        }
        // Query Scheme Not Found
        if (!method_exists($scheme, "Query")) {
            http_response_code(404);
            exit;
        }
        // Params Scheme Not Found
        if (!method_exists($scheme, "Params")) {
            http_response_code(404);
            exit;
        }

        // Return
        return [
            "body" => $scheme->Body(),
            "query" => $scheme->Query(),
            "params" => $scheme->Params(),
        ];
    }
}
