<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Delivery-Date
 * @subpackage Delivery-Date/public
 */
require_once plugin_dir_path(__FILE__) . '../woocommerce-delivery-schedular/public/class-delivery-date-public.php';
class Custom_Delivery_Date_Public extends Delivery_Date_Public {
	
	private $plugin_name;

	private $version;

	private $loader;

	function __construct( $plugin_name, $version, $loader ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->loader = $loader;

		$this->dld_define_public_hooks();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function dld_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name.'_for_only_public', plugins_url( '../woocommerce-delivery-schedular/public/css/delivery-date-public.css', __FILE__ ), array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function dld_enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name.'_for_public', plugins_url( '../woocommerce-delivery-schedular/public/js/delivery-date-public.js', __FILE__ ), array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name.'_for_public', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function dld_define_public_hooks() {
		
		$this->loader->add_action( 'woocommerce_single_product_summary', $this, 'dld_custom_action_after_single_product_title', 6 );
		$this->loader->add_action( 'woocommerce_add_cart_item_data', $this, 'dld_add_cart_item_custom_data_vase', 10, 2 );
	}

	public function dld_custom_action_after_single_product_title() {

		global $product; 

		$product_id = sanitize_text_field($product->get_id());
		update_option('custom_product_id',$product_id);
	}

	public function dld_add_cart_item_custom_data_vase( $cart_item_meta, $product_id ) {

		global $woocommerce;

		$option = get_option('delivery_date');
		$cart_item_meta['custom_date'] = $option;

		return $cart_item_meta; 
	}

}