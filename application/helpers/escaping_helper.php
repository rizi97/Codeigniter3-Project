<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('esc_html')) {
    function esc_html($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
