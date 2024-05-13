<?php

namespace OBVQP_WpPluginAutoload\Core;

use OBVQP_WpPluginAutoload\Admin\OBVQP_Admin;
use OBVQP_WpPluginAutoload\Frontend\OBVQP_Frontend;


/**
 * The core plugin class.
 */
class OBVQP_Plugin
{
    /**
     * The plugin's unique id.
     *
     * @var string
     */
    private $id;

    /**
     * @var OBVQP_Loader
     */
    private $loader;

    public function __construct($id, $version)
    {
        $this->id = $id;

        $this->loader = new OBVQP_Loader();
        $this->loader->add_action('plugins_loaded', $this, 'load_plugin_textdomain');

        $assets = new OBVQP_Assets($id, $version, $this->loader, is_admin());
        $templating = new OBVQP_Templating();

        if (is_admin()) {
            new OBVQP_Admin($this->loader, $assets, $templating);
        } else {
            new OBVQP_Frontend($this->loader, $assets, $templating);

        }
    }

    /**
     * Run the plugin.
     */
    public function run()
    {
        $this->loader->register_hooks();
        #$this->load_plugin_textdomain();
    }

    /**
     * Load internationalization files.
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            $this->id,
            $deprecated = false,
            $this->id.'/languages/'
        );
    }
}
