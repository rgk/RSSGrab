<?php
    /*
    Plugin Name: RSSGrab
    Plugin URI: http://madgizmo.com/
    Description: Grab a RSS feed and update content to a hook called rssgrab_init.
    Version: 1
    Author: Robert Kowalski
    Author URI: http://rgk.madgizmo.com/
    License: GPLv3
    */

    if ( !function_exists( 'add_action' ) ) {
        echo 'Don\'t access this plugin directly.';
        exit;
    }

    function activate_rssgrab() {
        include_once plugin_dir_path( __FILE__ ) . 'includes/class-rssgrab-activator.php';
        RSSGrab_Activator::activate();
    }

    function deactivate_rssgrab() {
        include_once plugin_dir_path( __FILE__ ) . 'includes/class-rssgrab-deactivator.php';
        RSSGrab_Deactivator::deactivate();
    }
    register_activation_hook( __FILE__, 'activate_rssgrab' );
    register_deactivation_hook( __FILE__, 'deactivate_rssgrab' );

    include plugin_dir_path( __FILE__ ) . 'includes/class-rssgrab.php';

    function run_rssgrab() {
        $plugin = new RSSGrab();
        $plugin->run();
    }
    run_rssgrab();
