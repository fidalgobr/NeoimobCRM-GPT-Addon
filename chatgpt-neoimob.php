<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://eniosoft.com.br
 * @since             1.0.0
 * @package           Chatgpt_Neoimob
 *
 * @wordpress-plugin
 * Plugin Name:       Integração ChatGPT + Neoimob
 * Plugin URI:        https://eniosoft.com.br
 * Description:       Este plugin integra o ChatGPT com o Neoimob para criação de textos de descrição
 * Version:           1.0.0
 * Author:            eniosoft
 * Author URI:        https://eniosoft.com.br/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       chatgpt-neoimob
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

include_once 'classes/Iniciador_api.php';
include_once 'classes/Iniciador_frontend.php';
include_once 'classes/Criador_tabela_plugin.php';

function run_chatgpt_neoimob()
{
	add_action('init', function () {
		$usuario_esta_autenticado = current_user_can('administrator') || current_user_can('editor');

		if ($usuario_esta_autenticado) {
			$iniciador_api = new Iniciador_api();
			$iniciador_frontend = new Iniciador_frontend();
			$iniciador_api->iniciar();
			$iniciador_frontend->injetar_frontend();
		}
	});
}
run_chatgpt_neoimob();
