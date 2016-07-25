<?php

    class RSSGrab {

        protected $loader;

        protected $plugin_name;

        protected $version;

        public function __construct() {
            $this->plugin_name = 'RSSGrab';
            $this->version = '1';
            $this->load_dependencies();
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }

        private function load_dependencies() {

            include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rssgrab-loader.php';

            include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rssgrab-admin.php';

            include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rssgrab-public.php';

            $this->loader = new RSSGrab_Loader();
        }

        private function define_admin_hooks() {
            $plugin_admin = new RSSGrab_Admin( $this->get_plugin_name(), $this->get_version() );
            //$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
            //$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

            // This creates the admin page.
            add_action( 'admin_menu', 'rssgrab_add_admin_menu' );
            add_action( 'admin_init', 'rssgrab_settings_init' );

            function rssgrab_add_admin_menu() {
                add_menu_page( 'RSSGrab', 'RSSGrab', 'manage_options', 'rssgrab', 'rssgrab_options_page' );
            }

            function rssgrab_settings_init() {
                register_setting( 'pluginPage', 'rssgrab_settings' );

                add_settings_section(
                    'rssgrab_pluginPage_section', 
                    __( 'Grab a RSS feed.', 'wordpress' ), 
                    'rssgrab_settings_section_callback', 
                    'pluginPage'
                );

                add_settings_field( 
                    'rssgrab_feed', 
                    __( 'Feed:', 'wordpress' ), 
                    'rssgrab_feed_render', 
                    'pluginPage', 
                    'rssgrab_pluginPage_section' 
                );
            }

            function rssgrab_feed_render() {
                $options = get_option('rssgrab_settings');
                ?>
                <input type='text' name='rssgrab_settings[rssgrab_feed]' style='width:100%' value='<?php echo $options['rssgrab_feed']; ?>'>
                <?php
            }

            function rssgrab_settings_section_callback() {
                echo __( 'Enter a feed to grab.', 'wordpress' );
            }

            function rssgrab_options_page() {
                ?>
                <form action='options.php' method='post'>

                    <h2>RSSGrab</h2>

                    <?php
                        settings_fields( 'pluginPage' );
                        do_settings_sections( 'pluginPage' );
                        submit_button();
                    ?>

                </form>
                <?php
            }
        }

        private function define_public_hooks() {
            $plugin_public = new RSSGrab_Public( $this->get_plugin_name(), $this->get_version() );
            //$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
            //$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

            // This creates the public rss feed content to place in template files.
            // Example: do_action('rssgrab_init');
            add_action( 'rssgrab_init', 'rssgrab_public_init' );

            function rssgrab_public_init() {
                rssgrab_public_page();
            }

            function rssgrab_public_page() {
                ?>
                <form action='options.php' method='post' class="site-content">

                    <h2 class='entry-header' style='padding-top: 1em;'>RSSGrab</h2>
                    <div class="entry-content">
                        <?php
                            include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/rssgrab-public-display.php';
                        ?>
                    </div>
                </form>
                <?php
            }
        }

        public function run() {
            $this->loader->run();
        }

        public function get_plugin_name() {
            return $this->plugin_name;
        }

        public function get_loader() {
            return $this->loader;
        }

        public function get_version() {
            return $this->version;
        }
}

?>
