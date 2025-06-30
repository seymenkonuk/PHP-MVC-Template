<?php
// ============================================================================
// File:    Controller.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Router.php");

class Controller
{
    public function View(string $view_name = "")
    {
        global $ViewData;
        // View Name Boş ise Varsayılanı Bul Getir (Kendi Adındakini)
        if ($view_name == "") {
            ["controller" => $controller, "action" => $action] = Router::getControllerAction();
            $view_name = "$controller" . DIRECTORY_SEPARATOR . "$action";
        }
        // Check File
        $path = VIEW . $view_name . ".php";
        if (!is_file($path)) {
            return $this->NotFound();
        }
        // Print
        ob_get_clean();
        ob_start();
        require_once($path);
        return ob_get_clean();
    }

    public function Redirect(string $url)
    {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->NotFound();
        }
        ob_get_clean();
        header("Location: $url");
        return "";
    }

    public function LocalRedirect(string $path)
    {
        // Validate Path
        if (!preg_match('/^\/[a-zA-Z0-9\/\-_\.]+$/', $path)) {
            return $this->NotFound();
        }
        ob_get_clean();
        header("Location: $path");
        return "";
    }

    public function Json(array $json_data)
    {
        return $this->Print(json_encode($json_data, JSON_UNESCAPED_UNICODE), "application/json");
    }

    public function JsonSuccess(string $message, array $data)
    {
        return $this->Json([
            "success" => true,
            "message" => $message,
            "data" => $data
        ]);
    }

    public function JsonFail(string $message, array $error)
    {
        return $this->Json([
            "success" => false,
            "message" => $message,
            "error" => $error
        ]);
    }

    public function Content(string $text, string $content_type = "plain/text")
    {
        return $this->Print($text, $content_type);
    }

    public function File(string $path, string $content_type)
    {
        if (!is_file($path)) {
            return $this->NotFound();
        }
        $content = file_get_contents($path);
        return $this->Print($content, $content_type);
    }

    public function StreamFile(string $path, string $content_type)
    {
        if (!is_file($path)) {
            return $this->NotFound();
        }

        $size = filesize($path);
        $start = 0;
        $end = $size - 1;
        $length = $size;

        if (
            isset($_SERVER['HTTP_RANGE']) &&
            preg_match('/bytes=(\d+)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches)
        ) {
            $start = intval($matches[1]);
            if (!empty($matches[2])) {
                $end = intval($matches[2]);
            }
            $length = $end - $start + 1;
            header("HTTP/1.1 206 Partial Content");
        } else {
            header("HTTP/1.1 200 OK");
        }

        header("Content-Type: $content_type");
        header("Accept-Ranges: bytes");
        header("Content-Length: $length");
        header("Content-Range: bytes $start-$end/$size");

        ob_get_clean();
        ob_start();

        $fp = fopen($path, 'rb');
        fseek($fp, $start);

        $bufferSize = 8192; // 8 KiB
        while (!feof($fp) && ftell($fp) <= $end) {
            echo fread($fp, $bufferSize);
            ob_flush();
            flush();
        }

        fclose($fp);
        return ob_get_clean();
    }

    public function StatusCode(int $code, string $error_message = "")
    {
        http_response_code($code);
        return $this->Print($error_message, "text/html");
    }

    public function EmptyResult()
    {
        return $this->StatusCode(204);
    }

    public function BadRequest(string $error_message = "")
    {
        return $this->StatusCode(400, $error_message);
    }

    public function Unauthorized(string $error_message = "")
    {
        return $this->StatusCode(401, $error_message);
    }

    public function Forbidden(string $error_message = "")
    {
        return $this->StatusCode(403, $error_message);
    }

    public function NotFound(string $error_message = "")
    {
        return $this->StatusCode(404, $error_message);
    }

    private function Print(string $message, string $content_type)
    {
        ob_get_clean();
        ob_start();
        header("Content-Type: $content_type");
        echo $message;
        return ob_get_clean();
    }
}
