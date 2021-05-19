<?php

/**
 * Plugin Name: Custom Delivery Date
 * Plugin URI:  http://www.kostali.com
 * Description: Custom Delivery Date
 * Version: 0.1.0
 * Author: Kostali Solutions 
 * Author URI: http://www.kostali.com
 * License: GPL2
 *
    
 * Kostali Solutions Private Limited (www.kostali.com)
 * 
 * 
 *  Copyright: (c)  [2019] - Kostali Solutions Private Limited ( contact@kostali.com  ) 
 *  All Rights Reserved.
 * 
 * NOTICE:  All information contained herein is, and remains
 * the property of Kostali Solutions Private Limited,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to Kostali Solutions Private Limited,
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Kostali Solutions Private Limited
 *
 *
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('Custom_Delivery_Date')) {

    define('CUSTOM_DELIVERY_DATE_VERSION', '0.1.0');

    require_once plugin_dir_path(__FILE__) . '/class-custom-delivery-schedular.php';

    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */

    add_action('wp_ajax_get_date', 'get_date');

    add_action('wp_ajax_nopriv_get_date', 'get_date');


    add_action('wp_enqueue_scripts', 'dld_delivery_date_enqueue_script');

    add_action('wp_ajax_date_current_slot', 'date_current_slot');
    add_action('wp_ajax_nopriv_date_current_slot', 'date_current_slot');

    add_action('wp_ajax_postal_code', 'postal_code');
    add_action('wp_ajax_nopriv_postal_code', 'postal_code');

    function postal_code()
    {
        $postal_id = $_POST['postal_code'];
        $id = $_POST['product_id'];
        $response = false;

        $options = get_option('dd_settings');
        $dates_options = get_option('dd_dates');
        $days_options = get_option('dd_days');
        $dates_range_options = get_option('dd_dates_ranges');
        $prefix = '_del_date_';

        if ($options['select_days_or_date'] == "override_days" && $options['allow_postal_code'] == "checked") {
            $get_code = $options['postal_code'];
            $get_post_code = (explode(",", $get_code));
            foreach ($get_post_code as $value) {
                if ($value == $postal_id) {
                    $response = true;
                }
            }
        } elseif ($options['select_days_or_date'] == "override_dates" && $options['allow_postal_code'] == "checked") {
            $get_code = $options['postal_code'];
            $get_post_code = (explode(",", $get_code));

            foreach ($get_post_code as $value) {
                if ($value == $postal_id) {
                    $response = true;
                }
            }
        } elseif ($options['select_days_or_date'] == "override_dates_range" && $options['allow_postal_code'] == "checked") {
            $get_code = $options['postal_code'];
            $get_post_code = (explode(",", $get_code));

            foreach ($get_post_code as $value) {
                if ($value == $postal_id) {
                    $response = true;
                }
            }
        }

        echo json_encode($response);
        wp_die();
    }
    function dld_delivery_date_enqueue_script()
    {
     
  
      wp_enqueue_script('delivery-date-enqueue-ui-scriptdf', 'https://code.jquery.com/ui/1.9.2/jquery-ui.js', array(
        'jquery'
      ));
  
      wp_enqueue_style('delivery-date-enqueue-ui-scriptnm,df', 'https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css');
  
      wp_enqueue_style('delivery-date-enqueue-ui-scriptnmbndf', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
     
      
    }

     function dld_enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name.'_for_public', plugins_url( '/js/delivery-date-public.js', __FILE__ ), array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name.'_for_public', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
   
    function get_date()
    {
        print_r($_POST);
        session_start();
        $_SESSION['sel_date'] = (array_key_exists('sel_date', $_POST))?$_POST['sel_date']:null;
        update_option('sel_date', $_SESSION['sel_date']);
        print_r($_SESSION);
        die();
    }
    
    function date_current_slot()
    {
        $id = $_POST['product_id'];
        $user_date = $_POST['date'];
        $day_of_week = $_POST['day_of_week'];
        $response = array();

        $Override_Delivery_dates = get_post_meta($id, '_del_date_Override_Delivery_dates', true);
        $prefix = '_del_date_';
        $Delivery_dates_days_check = get_post_meta($id, $prefix . 'my_key', true) ? get_post_meta($id, $prefix . 'my_key', true) : '';
        $day_of_week_meta = 'dd_'.$day_of_week.'_timeslots';
        

        $datetimes_slot = get_post_meta($id, $prefix . 'datetimes', true);
        $daystimes_slot = get_post_meta($id, $prefix . 'datetimes_days', true);
        $datestimes_slot = get_post_meta($id, $prefix . 'datetimes_dates', true);
        $datesrange_times_slot = get_post_meta($id, $prefix . 'datetimes_daterange', true);
        $day_of_week_timeslots = get_post_meta($id, $prefix. $day_of_week_meta, false );
        
        $options = get_option('dd_settings');
        $days_options = get_option('dd_days');
        $dates_options = get_option('dd_dates');
        $datesrange_options = get_option('dd_dates_ranges');
        $general_day_of_week_timeslots = get_option($day_of_week_meta);
        
        foreach ($general_day_of_week_timeslots as $value) {
            $response[$value] = $value;
        }
        
        foreach ($day_of_week_timeslots as $value) {
            $response[$value] = $value;
        }
       // echo json_encode($response);
       // wp_die();

        if ($Override_Delivery_dates == 'Override_Delivery_dates') {
            if ($Delivery_dates_days_check == 'Delivery_Days') {
                foreach ($daystimes_slot as $value) {
                    date_default_timezone_set("Europe/Rome");
                    $str = $value;
                    $slot_time = (explode("-", $str));
                    $time = date('H:i:A', strtotime($slot_time[1]));

                    if (strtotime(date("d-m-Y")) == strtotime($user_date)) {
                        if ($time > date('H:i:A')) {
                            $response[$value] = $value;
                        }
                    } else {
                        $response[$value] = $value;
                    }
                }
            } else if ($Delivery_dates_days_check == 'Delivery_dates') {
                foreach ($datestimes_slot as $value) {
                    date_default_timezone_set("Europe/Rome");
                    $str = $value;
                    $slot_time = (explode("-", $str));
                    $time = date('H:i:A', strtotime($slot_time[1]));

                    if (strtotime(date("d-m-Y")) == strtotime($user_date)) {
                        if ($time > date('H:i:A')) {
                            $response[$value] = $value;
                        }
                    } else {
                        $response[$value] = $value;
                    }
                }
            } else if ($Delivery_dates_days_check == 'Delivery_Deliveryday') {
                foreach ($datesrange_times_slot as $value) {
                    date_default_timezone_set("Europe/Rome");
                    $str = $value;
                    $slot_time = (explode("-", $str));
                    $time = date('H:i:A', strtotime($slot_time[1]));

                    if (strtotime(date("d-m-Y")) == strtotime($user_date)) {
                        if ($time > date('H:i:A')) {
                            $response[$value] = $value;
                        }
                    } else {
                        $response[$value] = $value;
                    }
                }
            }
        } else {
            if ($options['select_days_or_date'] == "override_days") {
                foreach ($days_options['days_datetimes'] as $value) {
                    date_default_timezone_set("Europe/Rome");
                    $str = $value;
                    $slot_time = (explode("-", $str));
                    $time = date('H:i:A', strtotime($slot_time[1]));

                    if (strtotime(date("d-m-Y")) == strtotime($user_date)) {
                        if ($time > date('H:i:A')) {
                            $response[$value] = $value;
                        }
                    } else {
                        $response[$value] = $value;
                    }
                }
            } elseif ($options['select_days_or_date'] == "override_dates") {
                foreach ($dates_options['dates_datetimes'] as $value) {
                    date_default_timezone_set("Europe/Rome");
                    $str = $value;
                    $slot_time = (explode("-", $str));
                    $time = date('H:i:A', strtotime($slot_time[1]));

                    if (strtotime(date("d-m-Y")) == strtotime($user_date)) {
                        if ($time > date('H:i:A')) {
                            $response[$value] = $value;
                        }
                    } else {
                        $response[$value] = $value;
                    }
                }
            } elseif ($options['select_days_or_date'] == "override_dates_range") {
                foreach ($datesrange_options['daterange_datetimes'] as $value) {
                    date_default_timezone_set("Europe/Rome");
                    $str = $value;
                    $slot_time = (explode("-", $str));

                    $time = date('H:i:A', strtotime($slot_time[1]));

                    if (strtotime(date("d-m-Y")) == strtotime($user_date)) {
                        if ($time > date('H:i:A')) {
                            $response[$value] = $value;
                        }
                    } else {
                        $response[$value] = $value;
                    }
                }
            }
        }


        echo json_encode($response);
        wp_die();
    }

    function dld_run_delivery_date()
    {
        $delivery_date = new Custom_Delivery_Date();
        $delivery_date->run();
    }

    function dld_apply_delivery_date_shortcode()
    {
        echo do_shortcode('[calender_p]');
    }
    
    $options = get_option('dd_settings');

    function dld_apply_delivery_date_shortcode1()
    {

        echo do_shortcode('[calender_p]');
    }

    $options = get_option('dd_settings');

    if (@$options['allow_product_page'] == "checked") {
        add_action('woocommerce_before_add_to_cart_button', 'dld_apply_delivery_date_shortcode');
    }
    if (@$options['allow_shop_page'] == "checked") {
        add_action('woocommerce_after_shop_loop_item', 'dld_apply_delivery_date_shortcode1');
    }
    dld_run_delivery_date();


    // Save Delivery Date on checkout and order page start

    function dld_add_engraving_text_to_cart_item($cart_item_data, $product_id, $variation_id)
    {

        session_start();
        if (empty($_SESSION['sel_date'])) {
            $cart_item_data['custom_date'] = "";
            session_destroy();
            return $cart_item_data;
        }
        $cart_item_data['custom_date'] = $_SESSION['sel_date'];
        session_destroy();
        return $cart_item_data;
    }

    add_filter('woocommerce_add_cart_item_data', 'dld_add_engraving_text_to_cart_item', 10, 3);



    function dld_display_engraving_text_cart($item_data, $cart_item)
    {


        if (empty($cart_item['custom_date'])) {

            $options = get_option('dd_settings');
            $id = $_POST['id'];
            if (empty($id)) {
                $id = get_option('custom_product_id');
            }
            $del_date = get_post_meta($id, '_del_date_Override_Delivery_dates', true);
            if ($del_date == 'Override_Delivery_dates') {
                $prefix = '_del_date_';
                $Delivery_dates_days = get_post_meta($id, $prefix . 'my_key', true) ? get_post_meta($id, $prefix . 'my_key', true) : '';
                $multi_select_day = get_post_meta($id, '_del_date_mul_sel_day', true) ? get_post_meta($id, '_del_date_mul_sel_day', true) : '';
                $multi_select_date = get_post_meta($id, '_del_date_mul_sel_date', true) ? get_post_meta($id, '_del_date_mul_sel_date', true) : '';
                $multi_select_daterange = get_post_meta($id, '_del_date_mul_Deliveryday_range', true) ? get_post_meta($id, '_del_date_mul_Deliveryday_range', true) : '';

                if ($Delivery_dates_days == "Delivery_Days") {

                    foreach ($multi_select_day as $key => $value) {

                        if ($value == "0") {
                            $days[] = "Sunday";
                        }
                        if ($value == "1") {
                            $days[] = "Monday";
                        }
                        if ($value == "2") {
                            $days[] = "Tuesday";
                        }
                        if ($value == "3") {
                            $days[] = "Wednesday";
                        }
                        if ($value == "4") {
                            $days[] = "Thursday";
                        }
                        if ($value == "5") {
                            $days[] = "Friday";
                        }
                        if ($value == "6") {
                            $days[] = "Saturday";
                        }
                    }
?>
                    <br>
                    <strong>Delivery Available on :</strong>
                    <?php

                    foreach ($days as $key1 => $value1) {
                        echo $value1 . ',';
                    }
                    ?>
                <?php
                    return $item_data;
                } else if ($Delivery_dates_days == "Delivery_dates") {
                    $multi_select_date_ar = explode(', ', $multi_select_date);
                    $arr_count = count($multi_select_date_ar); ?>
                    <br>
                    <strong>Delivery Between :</strong><?php echo $multi_select_date_ar['0']; ?><label>to</label><?php echo $multi_select_date_ar[$arr_count - 1];
                                                                                                                return $item_data;
                                                                                                            } else if ($Delivery_dates_days  == "Delivery_Deliveryday") {
                                                                                                                $daterange_arr_count = count($multi_select_daterange);
                                                                                                                $daterange_str = $multi_select_daterange[0];
                                                                                                                $pieces = explode("-", $daterange_str); ?>
                    <br>
                    <strong>Delivery Between :</strong><?php foreach ($multi_select_daterange as $rangekey => $rangevalue) {
                                                                                                                    $rangevaluepieces = explode("-", $rangevalue);
                                                                                                                    echo $rangevaluepieces['0']; ?><label>to</label>
                        <?php echo $rangevaluepieces['1'] . ","; ?><br>
                    <?php
                                                                                                                }
                                                                                                                return $item_data;
                                                                                                            } else if ($Delivery_dates_days == "Delivery_Holiday") {
                                                                                                                return $item_data;
                                                                                                            }
                                                                                                        } else if ($options) {

                                                                                                            if ($options['select_days_or_date'] == 'override_dates') {
                                                                                                                $options_dates = get_option('dd_dates');
                                                                                                                $dates_ar = explode(', ', $options_dates['disable_dates']);
                                                                                                                $n = count($dates_ar);
                    ?>
                    <html>
                    <br>
                    <strong>Delivery Between :</strong><?php echo $dates_ar['0']; ?><label>to</label><?php echo $dates_ar[$n - 1] ?>

                    </html>
                <?php
                                                                                                                return $item_data;
                                                                                                            } else if ($options['select_days_or_date'] == 'override_days') {
                                                                                                                $options_days = get_option('dd_days');
                                                                                                                foreach ($options_days['avail_days'] as $key => $value) {
                                                                                                                    if ($value == "0") {
                                                                                                                        $days[] = "Sunday";
                                                                                                                    }
                                                                                                                    if ($value == "1") {
                                                                                                                        $days[] = "Monday";
                                                                                                                    }
                                                                                                                    if ($value == "2") {
                                                                                                                        $days[] = "Tuesday";
                                                                                                                    }
                                                                                                                    if ($value == "3") {
                                                                                                                        $days[] = "Wednesday";
                                                                                                                    }
                                                                                                                    if ($value == "4") {
                                                                                                                        $days[] = "Thursday";
                                                                                                                    }
                                                                                                                    if ($value == "5") {
                                                                                                                        $days[] = "Friday";
                                                                                                                    }
                                                                                                                    if ($value == "6") {
                                                                                                                        $days[] = "Saturday";
                                                                                                                    }
                                                                                                                }
                ?>

                    <br>
                    <strong>Delivery Available on :</strong>
                    <?php

                                                                                                                foreach ($days as $key1 => $value1) {
                                                                                                                    echo $value1 . ',' . $del_date;
                                                                                                                }
                    ?>
                <?php

                                                                                                                return $item_data;
                                                                                                            } else if ($options['select_days_or_date'] == 'override_dates_range') {
                                                                                                                $options_dates_ranges = get_option('dd_dates_ranges');
                ?>
                    <br>
                    <strong>Delivery Between :</strong><?php foreach ($options_dates_ranges['deliveryday_slot'] as $rangekey1 => $rangevalue1) {
                                                                                                                    $rangevaluepieces1 = explode("-", $rangevalue1);
                                                                                                                    echo $rangevaluepieces1['0']; ?><label>to</label>
                        <?php echo $rangevaluepieces1['1'] . ","; ?><br>
        <?php
                                                                                                                }

                                                                                                                return $item_data;
                                                                                                            } else {
                                                                                                                return $item_data;
                                                                                                            }
                                                                                                        }
                                                                                                    }

                                                                                                    $item_data[] = array(
                                                                                                        'key'     => esc_attr('Delivery Date', 'Delivery-Date'),
                                                                                                        'value'   => wc_clean($cart_item['custom_date']),
                                                                                                        'display' => '',
                                                                                                    );
                                                                                                    return $item_data;
                                                                                                }


                                                                                                if (@$options['allow_cart_page'] == "checked") {

                                                                                                    add_filter('woocommerce_get_item_data', 'dld_display_engraving_text_cart', 10, 2);
                                                                                                }
                                                                                                function dld_add_engraving_text_to_order_items($item, $cart_item_key, $values, $order)
                                                                                                {
                                                                                                    if (empty($values['custom_date'])) {
                                                                                                        return;
                                                                                                    }

                                                                                                    $item->add_meta_data(__('Delivery Date', 'dld_delivery_date'), $values['custom_date']);
                                                                                                }

                                                                                                add_action('woocommerce_checkout_create_order_line_item', 'dld_add_engraving_text_to_order_items', 10, 4);

                                                                                                // Save Delivery Date on checkout and order page end

                                                                                                // Time Slot UI Start
                                                                                                function woocommerce_custom_fields_display()
                                                                                                {
                                                                                                    global $post;
                                                                                                    $product = wc_get_product($post->ID);
                                                                                                    $custom_fields_woocommerce_title = $product->get_meta('_del_date_mul_datetimes');
                                                                                                    $Override_Delivery_dates = $product->get_meta('_del_date_Override_Delivery_dates');

                                                                                                    $options = get_option('dd_settings');
                                                                                                    $days_options = get_option('dd_days');
                                                                                                    $dates_options = get_option('dd_dates');
                                                                                                    $datesrange_options = get_option('dd_dates_ranges');

                                                                                                    $prefix = '_del_date_';
                                                                                                    $Delivery_dates_days_check = get_post_meta($post->ID, $prefix . 'my_key', true) ? get_post_meta($post->ID, $prefix . 'my_key', true) : '';


                                                                                                    $datetimes_slot = get_post_meta($post->ID, $prefix . 'datetimes', true);
                                                                                                    $daystimes_slot = get_post_meta($post->ID, $prefix . 'datetimes_days', true);
                                                                                                    $datestimes_slot = get_post_meta($post->ID, $prefix . 'datetimes_dates', true);
                                                                                                    $datesrange_times_slot = get_post_meta($post->ID, $prefix . 'datetimes_daterange', true);

        ?>
        <table>
            <tbody>
                <tr>
                    <td>
                        <label><?php echo esc_html__('Select Time Slot', 'Delivery-Date'); ?></label>
                    </td>
                    <td><select name="time_slot" id="time_slot" class="select_time_slot">
                        <?php
                                if ($Override_Delivery_dates == 'Override_Delivery_dates') {
                                    if ($Delivery_dates_days_check == 'Delivery_Days') {
                                        if(!isset($value)) $value = "";
                        ?>
                        <option value="<?php echo $value; ?>"><?php echo esc_html__($value, 'Delivery-Date'); ?></option>
                        <?php } else if ($Delivery_dates_days_check == 'Delivery_dates') {

                                ?><option value="<?php echo $value; ?>"><?php echo esc_html__($value, 'Delivery-Date'); ?></option>
                        <?php  } else if ($Delivery_dates_days_check == 'Delivery_Deliveryday') { ?>
                        <option value="<?php echo $value; ?>"><?php echo esc_html__($value, 'Delivery-Date'); ?></option>
                        <?php }
                        } else {
                        if ($options['select_days_or_date'] == "override_days") {?>
                        <option value="<?php echo $value; ?>"><?php echo esc_html__($value, 'Delivery-Date'); ?></option>
                            <?php } elseif ($options['select_days_or_date'] == "override_dates") { ?>
                        <option value="<?php echo $value; ?>"><?php echo esc_html__($value, 'Delivery-Date'); ?></option>
                        <?php } elseif ($options['select_days_or_date'] == "override_dates_range") { ?>
                        <option value="<?php echo $value; ?>"><?php echo esc_html__($value, 'Delivery-Date'); ?></option>
                        <?php  } ?>
                        <?php } ?></select></td>
                </tr>
            </tbody>
        </table>

<?php
                                                                                                    // }
                                                                                                }
                                                                                                if (@$options['allow_product_page'] == "checked") {
                                                                                                    add_action('woocommerce_before_add_to_cart_button', 'woocommerce_custom_fields_display');
                                                                                                }
                                                                                                // Time Slot UI End


                                                                                                // Save Delivery Time Slot on checkout and order page start

                                                                                                function dld_add_timeslot_text_to_cart_item($cart_item_data, $product_id, $variation_id)
                                                                                                {
                                                                                                    $add_time_slot = filter_input(INPUT_POST, 'time_slot');

                                                                                                    if (empty($add_time_slot)) {
                                                                                                        return $cart_item_data;
                                                                                                    }

                                                                                                    $cart_item_data['time_slot'] = $add_time_slot;

                                                                                                    return $cart_item_data;
                                                                                                }

                                                                                                add_filter('woocommerce_add_cart_item_data', 'dld_add_timeslot_text_to_cart_item', 10, 3);

                                                                                                function dld_display_timeslot_text_cart($item_data, $cart_item)
                                                                                                {
                                                                                                    if (empty($cart_item['time_slot'])) {
                                                                                                        return $item_data;
                                                                                                    }

                                                                                                    $item_data[] = array(
                                                                                                        'key'     => esc_attr('Delivery Time', 'Delivery-Date'),
                                                                                                        'value'   => wc_clean($cart_item['time_slot']),
                                                                                                        'display' => '',
                                                                                                    );

                                                                                                    return $item_data;
                                                                                                }

                                                                                                add_filter('woocommerce_get_item_data', 'dld_display_timeslot_text_cart', 10, 2);

                                                                                                function dld_add_timeslot_text_to_order_items($item, $cart_item_key, $values, $order)
                                                                                                {
                                                                                                    if (empty($values['time_slot'])) {
                                                                                                        return;
                                                                                                    }

                                                                                                    $item->add_meta_data(__('Delivery Time', 'dld_delivery_date'), $values['time_slot']);
                                                                                                }

                                                                                                add_action('woocommerce_checkout_create_order_line_item', 'dld_add_timeslot_text_to_order_items', 10, 4);

                                                                                                // Save Delivery Time Slot on checkout and order page end


                                                                                                // mail reminder functionality start

                                                                                                function isa_add_cron_recurrence_interval($schedules)
                                                                                                {

                                                                                                    $schedules['every_three_minutes'] = array(
                                                                                                        'interval'  => 60,
                                                                                                        'display'   => __('Every 1 Minutes', 'textdomain')
                                                                                                    );

                                                                                                    return $schedules;
                                                                                                }
                                                                                                // add_filter('cron_schedules', 'isa_add_cron_recurrence_interval');


                                                                                                function isa_test_cron_job_send_mail()
                                                                                                {

                                                                                                    $orders = wc_get_orders(array('numberposts' => -1));

                                                                                                    // Loop through each WC_Order object
                                                                                                    foreach ($orders as $order) {
                                                                                                        $dd_order_id = $order->get_id(); // The order ID

                                                                                                        $order_meta = get_post_meta($dd_order_id);



                                                                                                        // // Loop through each order post object

                                                                                                        $order = wc_get_order($dd_order_id);
                                                                                                        $get_all_item_info = $order->get_items();

                                                                                                        foreach ($get_all_item_info as $item_id => $item) {

                                                                                                            // Here you get your data
                                                                                                            $order_delivery_date = wc_get_order_item_meta($item_id, 'Delivery Date', true);
                                                                                                            $order_delivery_time = wc_get_order_item_meta($item_id, 'Delivery Time', true);

                                                                                                            $order_date_and_time =  $order_delivery_date . ' ' . $order_delivery_time;

                                                                                                            $tokens = explode('-', $order_date_and_time);
                                                                                                            array_pop($tokens);
                                                                                                            $newString = implode('-', $tokens);

                                                                                                            $product_id = $item['product_id'];
                                                                                                            $product_variation_id = $item['variation_id'];

                                                                                                            $product_name = $item['name'];
                                                                                                            $product_qty = $item->get_quantity();
                                                                                                            $product_total = $item->get_total();
                                                                                                        }

                                                                                                        $get_timezone = get_option('timezone_string');
                                                                                                        date_default_timezone_set($get_timezone);

                                                                                                        $current_timezone = date('d-m-Y h:i A');

                                                                                                        $get_time = explode('-', $order_delivery_time);
                                                                                                        $get_start_time = $get_time[0];

                                                                                                        $options = get_option('dd_settings');
                                                                                                        $a =  explode(':', $get_start_time);

                                                                                                        // single product mail functionality start
                                                                                                        //      $Override_Delivery_dates = get_post_meta($product_id,'_del_date_Override_Delivery_dates');
                                                                                                        // $allow_mail_notification = get_post_meta($product_id,'_del_date_allow_notification_mail_single_product');
                                                                                                        // $allow_mail_notification_before_time = get_post_meta($product_id,'_del_date_allow_notification_mail_time_before_delivery');


                                                                                                        // if ($Override_Delivery_dates[0] == 'Override_Delivery_dates' && $allow_mail_notification[0] == 'allow_notification_mail_single_product') {

                                                                                                        //     $b = $a[0] - $allow_mail_notification_before_time[0];

                                                                                                        // }
                                                                                                        // else{
                                                                                                        $b = $a[0] - $options['allow_notification_mail_time_before_delivery'];

                                                                                                        // }
                                                                                                        // single product mail functionality end



                                                                                                        $num_padded = sprintf("%02d", $b);

                                                                                                        $final_notification_time = $order_delivery_date . ' ' . $num_padded . ':' . $a[1];

                                                                                                        $order_email = $order_meta['_billing_email'][0];
                                                                                                        $admin_email = get_option('admin_email');
                                                                                                        $trim_final_notification_time = str_replace(' ', '', $final_notification_time);
                                                                                                        $trim_current_timezone = str_replace(' ', '', $current_timezone);

                                                                                                        $timestamp_current_timezone = strtotime($current_timezone);
                                                                                                        $timestamp_final_notification_time = strtotime($final_notification_time);


                                                                                                        //
                                                                                                        $email = $admin_email;
                                                                                                        $message = '<div>';
                                                                                                        $message .= '<table border = "1">';
                                                                                                        $message .= '<tr>';
                                                                                                        $message .= '<td colspan = "3" style="text-align: center;padding: 10px;background-color: #96588a;color: #fff;">Delivery Reminder</td>';
                                                                                                        $message .= '</tr>';
                                                                                                        $message .= '<tr>';
                                                                                                        $message .= '<td colspan = "3">';
                                                                                                        $message .= '<p>Hi ' . $order_meta["_shipping_first_name"][0] . ',</p>';
                                                                                                        $message .= '<p>Just to let you know — your order <span style="font-weight: bold; color:#96588a; ">#' . $order->id . '</span> has been delivered between <span style="font-weight: bold; color:#96588a; ">' . $order_delivery_time . '</span></p>
              </td>';
                                                                                                        $message .= '</tr>';
                                                                                                        $message .= '<tr>';
                                                                                                        $message .= '<th>Product</th>';
                                                                                                        $message .= '<th>Quantity</th>';
                                                                                                        $message .= '<th>Price</th>';
                                                                                                        $message .= '</tr>';
                                                                                                        $message .= '<tr>';
                                                                                                        $message .= '<td rowspan = "2" style="padding: 10px;">' . $product_name . '</td>';
                                                                                                        $message .= '<td style="padding: 10px;">' . $product_qty . '</td>';
                                                                                                        $message .= '<td style="padding: 10px;">' . $product_total . '</td>';
                                                                                                        $message .= '</tr>';
                                                                                                        $message .= '</table>';
                                                                                                        $message .= '</div>';

                                                                                                        $to = $order_email;
                                                                                                        $subject = "Delivery Reminder";
                                                                                                        $headers = 'From: ' . $email . "\r\n" .
                                                                                                            'Reply-To: ' . $email . "\r\n";
                                                                                                        $headers .= 'Content-Type: text/html; charset="UTF-8"';

                                                                                                        if ($options['allow_notification_mail'] == 'checked') {
                                                                                                            $trim_final_notification_time = str_replace(' ', '', $final_notification_time);
                                                                                                            $trim_current_timezone = str_replace(' ', '', $current_timezone);

                                                                                                            $timestamp_current_timezone = strtotime($current_timezone);
                                                                                                            $timestamp_final_notification_time = strtotime($final_notification_time);

                                                                                                            if ($timestamp_current_timezone == $timestamp_final_notification_time) {
                                                                                                                // $send = wp_mail($to, $subject, strip_tags($message), $headers);
                                                                                                                $send = wp_mail($to, $subject, $message, $headers);
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }

                                                                                                // mail reminder functionality end

                                                                                            }
