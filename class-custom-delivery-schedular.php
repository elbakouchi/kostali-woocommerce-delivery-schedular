<?php
require_once plugin_dir_path(__FILE__) . '../woocommerce-delivery-schedular/includes/class-delivery-date.php';
//require_once plugin_dir_path(__FILE__) . '/class-custom-delivery-date-public.php';
class Custom_Delivery_Date extends Delivery_Date
{

    protected $loader;

    protected $plugin_name;

    protected $version;

    static $instance;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    function __construct()
    {
        parent::__construct();

        if (defined('CUSTOM_DELIVERY_DATE_VERSION')) {
            $this->version = CUSTOM_DELIVERY_DATE_VERSION;
        }
        $this->plugin_name = 'custom-delivery-date';

        $this->dld_load_dependencies();
        $this->dld_define_admin_hooks();
        $this->dld_define_public_hooks();
    }

    private function dld_define_admin_hooks() {

		$plugin_admin = new Custom_Delivery_Date_Admin( $this->dld_get_plugin_name(), $this->dld_get_version(), $this->dld_get_loader() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'dld_enqueue_styles',10,1 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'dld_enqueue_scripts',10,1 );
    }
    
    private function dld_define_public_hooks() {

		$plugin_public = new Custom_Delivery_Date_Public( $this->dld_get_plugin_name(), $this->dld_get_version(), $this->dld_get_loader() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dld_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'dld_enqueue_scripts' );
	}

    private function dld_load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/woocommerce-delivery-schedular/includes/class-delivery-date-hooks.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/woocommerce-delivery-schedular/includes/class-delivery-date-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/woocommerce-delivery-schedular/includes/class-delivery-date-ajax.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/woocommerce-delivery-schedular/includes/class-delivery-date-shortcodes.php';

        require_once  dirname( __FILE__ )  . '/class-custom-delivery-date-admin.php';
        require_once  dirname( __FILE__ )  . '/class-custom-delivery-date-public.php';
        
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . '/woocommerce-delivery-schedular/public/class-delivery-date-public.php';

		$this->loader = new Delivery_Date_Hooks();
	}
}
