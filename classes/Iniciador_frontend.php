<?php

class Iniciador_frontend
{
    public function injetar_frontend()
    {
        function injetar()
        {
            $target_url = '/novo-imovel/';

            if (strpos($_SERVER['REQUEST_URI'], $target_url) !== false) {
                wp_enqueue_script('axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', array(), '1.0', true);
                wp_enqueue_script('gpt-modulo-frontend', plugin_dir_url(__FILE__) . '../js/gpt-modulo-frontend.js', array(), '1.0', true);
            }
        }
        add_action('wp_enqueue_scripts', 'injetar');
    }
}