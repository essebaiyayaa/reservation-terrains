<?php

/**
 * URL Helper Functions
 */
class UrlHelper {
    /**
     * Generate a URL with the correct base path
     * 
     * @param string $path Path relative to base (e.g., 'login', 'terrains')
     * @return string Full URL
     */
    public static function url(string $path = ''): string {
        $path = ltrim($path, '/');
       return rtrim($_ENV['SITE_URL'], '/') . '/' . $path;

    }

    /**
     * Generate an asset URL
     * 
     * @param string $path Asset path (e.g., 'css/style.css')
     * @return string Full asset URL
     */
    public static function asset(string $path): string {
        $path = ltrim($path, '/');
       return rtrim($_ENV['SITE_URL'], '/') . '/' . $path;

    }

    /**
     * Redirect to a URL
     * 
     * @param string $path Path to redirect to
     * @return void
     */
    public static function redirect(string $path): void {
        header('Location: ' . self::url($path));
        exit;
    }
}