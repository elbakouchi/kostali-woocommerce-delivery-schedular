<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Delivery-Date
 * @subpackage Delivery-Date/admin
 */

class Custom_Delivery_Date_Admin
{

  private $plugin_name;

  private $version;

  private $loader;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name		The name of this plugin.
   * @param      string    $version    		The version of this plugin.
   */
  public function __construct($plugin_name, $version, $loader)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->loader = $loader;

    $this->dd_weekdays_select_field();
    $this->dld_load_dependencies();
    $this->dld_define_admin_hooks();

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function dld_define_admin_hooks()
  {

    $this->dld_admin_settings();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function dld_load_dependencies()
  {
    require_once plugin_dir_path(__FILE__) . '../woocommerce-delivery-schedular/admin/settings-data.php';
    require_once plugin_dir_path(__FILE__) . 'class-custom-delivery-date-admin-settings.php';
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function dld_enqueue_styles($hook)
  {

    if ($_GET['page'] == 'woocommerce-delivery-schedular') {
      wp_enqueue_style('jquery-ui-style', plugins_url() . '/woocommerce/assets/css/jquery-ui/jquery-ui.min.css', array(), $this->version, 'all');
      wp_enqueue_style('bootstrap-style-questionmark', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
      wp_enqueue_style('WOO-QB-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
      wp_enqueue_style('WOO-QB-font-awesome1', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css');
      wp_enqueue_style($this->plugin_name . '_for_admin', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/css/delivery-date-admin.css', array(), $this->version, 'all');
      wp_enqueue_style($this->plugin_name . '_for_custom_admin', plugin_dir_url(__FILE__) . '/custom-delivery-date-admin.css', array(), $this->version, 'all');
    }
    global $post, $wpdb;
    if ($hook == 'post-new.php' || $hook == 'post.php' || $_GET['page'] == 'woocommerce-delivery-schedular') {
      if ('product' === $post->post_type || $_GET['page'] == 'woocommerce-delivery-schedular') {
        wp_enqueue_style($this->plugin_name . '_for_admin', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/css/delivery-date-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '_for_custom_admin', plugin_dir_url(__FILE__) . '/custom-delivery-date-admin.css', array(), $this->version, 'all');
      }
    }

    wp_enqueue_style($this->plugin_name . '_for_multiDatepicker', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/css/jquery-ui.multidatespicker.css', array(), $this->version, 'all');

    wp_enqueue_style($this->plugin_name . '_for_daterangepicker', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/css/daterangepicker.css', array(), $this->version, 'all');
  }
  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function dld_enqueue_scripts($hook)
  {
    if ($_GET['page'] == 'woocommerce-delivery-schedular') {
      wp_enqueue_script('jquery-ui-datepicker');
      wp_enqueue_script('WOO-QB-bootstrap-javascript', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
    }
    global $post;
    if ($hook == 'post-new.php' || $hook == 'post.php' || $_GET['page'] == 'woocommerce-delivery-schedular') {
      if ('product' === $post->post_type || $_GET['page'] == 'woocommerce-delivery-schedular') {
        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/js/delivery-date-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-custom-admin', plugin_dir_url(__FILE__) . '/custom-delivery-date-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-multiDatepicker', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/js/jquery-ui.multidatespicker.js', array('jquery'), $this->version, false);

        wp_enqueue_script($this->plugin_name . '-moment', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/js/moment.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-daterangepicker', plugin_dir_url(__FILE__) . '../woocommerce-delivery-schedular/admin/js/daterangepicker.min.js', array('jquery'), $this->version, false);
      }
    }
  }

  /**
   * add Week days select to settings 
   */
  private function dd_weekdays_select_field()
  {
    $week_days_timeslots_fields = array( 
      'sections'	=> array(
          array(
              'id'				=> 'dd_dash_current_dates_section', 
              'title'			=> '', 
              'desc'			=> '',
          ),
      ),
      'fields'		=> array(
              array(
                'id'					=> 'dd_dash_monday_field', 
                'title'				=> 'Monday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              ),
              array(
                'id'					=> 'dd_dash_tuesday_field', 
                'title'				=> 'Tuesday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              ),
              array(
                'id'					=> 'dd_dash_wednesday_field', 
                'title'				=> 'Wednesday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              ),
              array(
                'id'					=> 'dd_dash_thursday_field', 
                'title'				=> 'Thursday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              ),
              array(
                'id'					=> 'dd_dash_friday_field', 
                'title'				=> 'Friday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              ),
              array(
                'id'					=> 'dd_dash_saturday_field', 
                'title'				=> 'Saturday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              ),
              array(
                'id'					=> 'dd_dash_sunday_field', 
                'title'				=> 'Sunday', 
                'section_id'	=> 'dd_dash_current_dates_section',
              )
        )
    );
  
    $GLOBALS['dd_weekdays_timeslots'] = $week_days_timeslots_fields ;
  }

  /**
   * Register all of the hooks related to the admin settings
   * of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   */
  protected function dld_admin_settings()
  {
    
    $admin_settings = new Custom_Delivery_Date_Admin_Settings($GLOBALS['dd_settings'], $GLOBALS['dd_days'], $GLOBALS['dd_dates'], $GLOBALS['dd_dates_ranges'], $GLOBALS['dd_holidays_slot'], $GLOBALS['dd_weekdays_timeslots'] );
    $this->loader->add_action('admin_menu', $admin_settings, 'dld_add_admin_menu');
    $this->loader->add_action('admin_init', $admin_settings, 'dld_settings_init');
    $this->loader->add_action('add_meta_boxes', $admin_settings, 'dld_add_meta_box');
    $this->loader->add_action('save_post', $admin_settings, 'dld_save_meta_box_fields');
  }
}
