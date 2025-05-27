<?php

namespace Core;

class View
{
    /**
     * Render a view file
     *
     * @param string $view The view file name
     * @param array $data Data to be passed to the view
     * @return string
     */
    public static function render($view, $data = [])
    {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewPath = dirname(__DIR__) . '/resources/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View file not found: {$view}");
        }

        // Get the contents of the buffer and clean it
        return ob_get_clean();
    }
}
