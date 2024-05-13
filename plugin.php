<?php
/*
 * @wordpress-plugin
 * Plugin Name:       OnionBuzz
 * Plugin URI:        http://onionbuzz.com/
 * Description:       OnionBuzz provides entertaining, viral content and quizzes worth sharing with friends. We are focused on community-based and powerful buzz tools for our audience.
 * Version:           1.2.7
 * Author:            Looks Awesome
 * Author URI:        https://looks-awesome.com/
 * License:           Commercial License
 * Text Domain:       onionbuzz
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die; // Forbid direct execution
}

require_once __DIR__.'/autoload.php';

register_activation_hook(__FILE__, array('OBVQP_WpPluginAutoload\OBVQP_Lifecycle', 'activate'));
register_deactivation_hook(__FILE__, array('OBVQP_WpPluginAutoload\OBVQP_Lifecycle', 'deactivate'));

define( 'OBVQP_PLUGIN_URL', plugin_dir_path(__FILE__ ) );
define( 'OBVQP_PLUGIN_URL_HTTP', plugin_dir_url(__FILE__ ) );
define( 'OBVQP_PLUGIN_BASENAME', plugin_basename(__FILE__));

$OBVQP_oConfig = new OBVQP_WpPluginAutoload\Core\OBVQP_Config();
$OBVQP_configs = $OBVQP_oConfig->get();

$OBVQP_wpPluginId = $OBVQP_configs['onionbuzz_info']['OB_PLUGIN_ID'];
$OBVQP_wpPluginVersion = $OBVQP_configs['onionbuzz_info']['OB_PLUGIN_V'];

define( 'OBVQP_PLUGIN_TEXTDOMAIN', $OBVQP_configs['onionbuzz_info']['OB_TEXTDOMAIN'] );

$OBVQP_WpPlugin = new OBVQP_WpPluginAutoload\Core\OBVQP_Plugin($OBVQP_wpPluginId, $OBVQP_wpPluginVersion);
$OBVQP_WpPlugin->run();

/*if (class_exists('WP_CLI')) {
    // Register WP-CLI commands
    foreach (glob(__DIR__.'/src/Command/*Command.php') as $path) {
        $class = '\\WpPlugin\\'.str_replace(array(__DIR__.'/src/', '.php', '/'), array('', '', '\\'), $path);

        // Read the @slug annotation from the PHPDoc of the class
        $reflection = new ReflectionClass(new $class());
        preg_match_all('/@slug (.*?)\n/s', $reflection->getDocComment(), $annotations);
        $slug = array_pop($annotations[1]);

        if (!empty($slug)) {
            WP_CLI::add_command($slug, $class);
        }
    }
}*/
