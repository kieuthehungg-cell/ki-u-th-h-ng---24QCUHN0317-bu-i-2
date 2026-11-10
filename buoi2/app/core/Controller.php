<?php
// app/core/Controller.php

abstract class Controller
{
    protected function view(string $view, array $data = [], string|false $layout = 'main'): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            die("Không tìm thấy view: " . htmlspecialchars($view));
        }

        $content = function () use ($viewFile, $data) {
            extract($data);
            require $viewFile;
        };

        if ($layout === false) {
            $content();
            return;
        }

        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            http_response_code(500);
            die("Không tìm thấy layout: " . htmlspecialchars($layout));
        }

        require $layoutFile;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . base_url($path));
        exit;
    }
}
