<?php

if (!function_exists('view')) {
    /**
     * Render a view file
     *
     * @param string $view The view file name
     * @param array $data Data to be passed to the view
     * @return string
     */
    function view($view, $data = [])
    {
        return \Core\View::render($view, $data);
    }
} 