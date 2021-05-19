<?php

/**
 * The admin settings class of the plugin.
 *
 * @link       https://kostali.com/
 * @since      1.0.0
 *
 * @package    Delivery-Date
 * @subpackage Delivery-Date/admin
 */

class Custom_Delivery_Date_Admin_Settings
{

    private $dd_settings;
    private $dd_days;
    private $dd_dates;
    private $dd_dates_ranges;
    private $dd_holidays;
    private $dd_weekdays_timeslots;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name   The name of this plugin.
     * @param      string    $version       The version of this plugin.
     */
    public function __construct($dd_settings, $dd_days, $dd_dates, $dd_dates_ranges, $dd_holidays, $dd_weekdays_timeslots=null)
    {

        $this->dd_settings = $dd_settings;
        $this->dd_days = $dd_days;
        $this->dd_dates = $dd_dates;
        $this->dd_dates_ranges = $dd_dates_ranges;
        $this->dd_holidays = $dd_holidays;
        $this->dd_weekdays_timeslots = $dd_weekdays_timeslots;



        add_action('wp_ajax_fun2', 'fun2');

        add_action('wp_ajax_nopriv_fun2', 'fun2');

        function fun2()
        {

            echo "<form id ='123'>" . "</form>";


            //die();
        }
    }


    /**
     * Add new menu & menu page to admin dashboard.
     *
     * @since    1.0.0
     */
    public function dld_add_admin_menu()
    {

        add_menu_page(
            __('Fine Delivery Scheduler Dashboard Settings', 'dd-dash'),
            __('Fine Delivery Scheduler Dashboard', 'dd-dash'),
            'manage_options',
            'woocommerce-delivery-schedular',
            [$this, 'dld_settings_page_cb'],
            'dashicons-dashboard'
        );
    }

    /**
     * Initialize setting sections & fields.
     *
     * @since    1.0.0
     */
    public function dld_settings_init()
    {
        register_setting('dd-dashboard', 'dd_settings');
        register_setting('dd-dashboard1', 'dd_days');
        register_setting('dd-dashboard2', 'dd_dates');
        register_setting('dd-dashboard3', 'dd_dates_ranges');
        register_setting('dd-dashboard4', 'dd_holidays');
        register_setting('dd-dashboard5', 'dd_monday_timeslots');
        register_setting('dd-dashboard5', 'dd_tuesday_timeslots');
        register_setting('dd-dashboard5', 'dd_wednesday_timeslots');
        register_setting('dd-dashboard5', 'dd_thursday_timeslots');
        register_setting('dd-dashboard5', 'dd_friday_timeslots');
        register_setting('dd-dashboard5', 'dd_sunday_timeslots');
        register_setting('dd-dashboard5', 'dd_saturday_timeslots');

        foreach ($this->dd_settings['sections'] as $section) {

            add_settings_section(
                $section['id'],
                __('<h5>' . $section['title'] . '</h5>', 'dd-dash'),
                [$this, 'dld_settings_section_cb'],
                'dd-dashboard'
            );
        }

        foreach ($this->dd_settings['fields'] as $field) {

            add_settings_field(
                $field['id'],
                __($field['title'], 'dd-dash'),
                [$this, 'dld_settings_field_cb'],
                'dd-dashboard',
                $field['section_id'],
                [
                    'label_for' => $field['id'],
                    'class'     => 'dd_dash_row',
                ]
            );
        }

        foreach ($this->dd_days['sections'] as $section) {

            add_settings_section(
                $section['id'],
                __('<h5>' . $section['title'] . '</h5>', 'dd-dash'),
                [$this, 'dld_settings_section_cb'],
                'dd-dashboard1'
            );
        }

        foreach ($this->dd_days['fields'] as $field) {

            add_settings_field(
                $field['id'],
                __($field['title'], 'dd-dash'),
                [$this, 'dld_settings_field_cb'],
                'dd-dashboard1',
                $field['section_id'],
                [
                    'label_for' => $field['id'],
                    'class'     => 'dd_dash_row',
                ]
            );
        }

        foreach ($this->dd_dates['sections'] as $section) {

            add_settings_section(
                $section['id'],
                __('<h5>' . $section['title'] . '</h5>', 'dd-dash'),
                [$this, 'dld_settings_section_cb'],
                'dd-dashboard2'
            );
        }

        foreach ($this->dd_dates['fields'] as $field) {

            add_settings_field(
                $field['id'],
                __($field['title'], 'dd-dash'),
                [$this, 'dld_settings_field_cb'],
                'dd-dashboard2',
                $field['section_id'],
                [
                    'label_for' => $field['id'],
                    'class'     => 'dd_dash_row',
                ]
            );
        }

        foreach ($this->dd_dates_ranges['sections'] as $section) {

            add_settings_section(
                $section['id'],
                __('<h5>' . $section['title'] . '</h5>', 'dd-dash'),
                [$this, 'dld_settings_section_cb'],
                'dd-dashboard3'
            );
        }

        foreach ($this->dd_dates_ranges['fields'] as $field) {

            add_settings_field(
                $field['id'],
                __($field['title'], 'dd-dash'),
                [$this, 'dld_settings_field_cb'],
                'dd-dashboard3',
                $field['section_id'],
                [
                    'label_for' => $field['id'],
                    'class'     => 'dd_dash_row',
                ]
            );
        }


        foreach ($this->dd_weekdays_timeslots['sections'] as $section) {

            add_settings_section(
                $section['id'],
                __('<h5>' . $section['title'] . '</h5>', 'dd-dash'),
                [$this, 'dld_settings_section_cb'],
                'dd-dashboard5'
            );
        }

        foreach ($this->dd_weekdays_timeslots['fields'] as $field) {

            add_settings_field(
                $field['id'],
                __($field['title'], 'dd-dash'),
                [$this, 'dld_settings_field_cb'],
                'dd-dashboard5',
                $field['section_id'],
                [
                    'label_for' => $field['id'],
                    'class'     => 'dd_dash_row',
                ]
            );
        }

        foreach ($this->dd_holidays['sections'] as $section) {

            add_settings_section(
                $section['id'],
                __('<h5>' . $section['title'] . '</h5>', 'dd-dash'),
                [$this, 'dld_settings_section_cb'],
                'dd-dashboard4'
            );
        }

        foreach ($this->dd_holidays['fields'] as $field) {

            add_settings_field(
                $field['id'],
                __($field['title'], 'dd-dash'),
                [$this, 'dld_settings_field_cb'],
                'dd-dashboard4',
                $field['section_id'],
                [
                    'label_for' => $field['id'],
                    'class'     => 'dd_dash_row',
                ]
            );
        }
    }

    private function echo_dd_weekday_timeslots($day, $daily_timeslots){
        $daterange_results_day = 'daterange_results_' . $day;
        echo '<div class="daterange_wrapper">';
        echo '<div class="'.$daterange_results_day.'">';

        $input_id      = 'dd_'.$day.'_timeslots';
        $input_name    = 'dd_'.$day.'_timeslots[]';
        $add_button    = 'daterange_clone_' . $day;
        $remove_button = 'daterange_remove_' . $day;
        
        $id = $input_id . '_0';
        echo "<input type=\"text\" id=\"$id\" class=\"daterange_datetimes daterange_timeslots\" value=\"\" autocomplete=\"off\" style=\"display: none;\" />";
        
        if (is_array($daily_timeslots) ){
           if( array_key_exists($input_name,  $daily_timeslots)){
                foreach ($daily_timeslots[$input_name] as $index => $value) {
                    $id = $input_id . '_' . $index;
                    echo "<input type=\"text\" id=\"$id\" name=\"$input_name\" class=\"daterange_datetimes daterange_timeslots\" value=\"$value\" autocomplete=\"off\" />";
                }
            } elseif( array_key_exists($input_id,  $daily_timeslots)){
                foreach ($daily_timeslots[$input_id] as $index => $value) {
                    $id = $input_id . '_' . $index;
                    echo "<input type=\"text\" id=\"$id\" name=\"$input_name\" class=\"daterange_datetimes daterange_timeslots\" value=\"$value\" autocomplete=\"off\" />";
                }
            } elseif (count($daily_timeslots)){
                foreach ($daily_timeslots as $index => $value){
                    $id = $input_id . '_' . $index;
                    echo "<input type=\"text\" id=\"$id\" name=\"$input_name\" class=\"daterange_datetimes daterange_timeslots\" value=\"$value\" autocomplete=\"off\" />";
                }
            } 
        }
                                                                                                                                         
        echo '</div>';
        echo '<div class="daterange_buttons">';

        echo   '<span class="_daterange_clone" id="'  . $add_button    . '">Add</span>';
        echo   '<span class="_daterange_remove" id="' . $remove_button . '">Cancel</span>';


        echo '</div>';
        echo '</div>';
        echo '<hr>';
    }
    /**
     * @param string $day
     */
    private function echo_dd_weekday_timeslot_fields($day){
        $Day = ucfirst($day);
        echo "<p><span>$Day</span></p>";
       // echo "<input type=\"hidden\" id=\"$day\" name=\"$day\"/>";
    }

    /**
     * Callback to render section fields called upon add settings field.
     *
     * @since    1.0.0
     */
    public function dld_settings_field_cb($args)
    {
        $options = get_option('dd_settings');
        $days_options = get_option('dd_days');
        $dates_options = get_option('dd_dates');
        $dates_range_options = get_option('dd_dates_ranges');
        $holidays_options = get_option('dd_holidays');
        $monday_timeslots  = get_option('dd_monday_timeslots');
        $tuesday_timeslots = get_option('dd_tuesday_timeslots');
        $wednesday_timeslots  = get_option('dd_wednesday_timeslots');
        $thursday_timeslots  = get_option('dd_thursday_timeslots');
        $friday_timeslots  = get_option('dd_friday_timeslots');
        $saturday_timeslots  = get_option('dd_saturday_timeslots');
        $sunday_timeslots  = get_option('dd_sunday_timeslots');
        
        $label_for = $args['label_for'];

        switch ($label_for) {

            case 'dd_dash_week_days_field':
                $week_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                echo '<select id="weekDay" class="">';
                echo '<option id="nothing"   value="" selected>--</option>';
                foreach ($week_days as $index => $week_day) {
                    echo "<option id=\"day-$index\"  value=\"$index\">$week_day</option>";
                }
                echo '</select>';
                break;
            case 'dd_dash_sunday_field':
               // $this->echo_dd_weekday_timeslot_fields('sunday');
                $this->echo_dd_weekday_timeslots('sunday', $sunday_timeslots);
                break;
            case 'dd_dash_monday_field':
                $this->echo_dd_weekday_timeslots('monday', $monday_timeslots);
                break;
            case 'dd_dash_tuesday_field':
                $this->echo_dd_weekday_timeslots('tuesday', $tuesday_timeslots);
                break; 
            case 'dd_dash_wednesday_field':
                $this->echo_dd_weekday_timeslots('wednesday', $wednesday_timeslots);
                break;                           
            case 'dd_dash_thursday_field':
                $this->echo_dd_weekday_timeslots('thursday', $thursday_timeslots);
                break;
            case 'dd_dash_friday_field':
                $this->echo_dd_weekday_timeslots('friday', $friday_timeslots);
                break; 
            case 'dd_dash_saturday_field':
                $this->echo_dd_weekday_timeslots('saturday', $saturday_timeslots);
                break;    
            case 'dd_dash_delivery_days_field':
                $week_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $avail_days = '';
                foreach ($week_days as $index => $week_day) {
                    if (array_key_exists('avail_days', $days_options) && !empty($days_options['avail_days'])) {

                        $avail_days = in_array($index, $days_options['avail_days']) ? 'checked=checked' : '';
                    }
                    echo '<input type="checkbox" name="dd_days[avail_days][]" value="' . $index . '" ' . $avail_days . ' />' . esc_html__($week_day, 'Delivery-Date');
                    echo '<br>';
                }

                break;

            case 'dd_dash_disable_dates_field':
                echo '<input id="datePick" name="dd_dates[disable_dates]" type="text" class="input-small" value="' . esc_attr($dates_options['disable_dates']) . '" autocomplete="off" />';
                echo "<div class='height'></div>";

                break;

            case 'dd_dash_select_postal_code':
?>
                <input type="checkbox" name="dd_settings[allow_postal_code]" value="checked" <?php echo (array_key_exists('allow_postal_code', $options))? $options['allow_postal_code']: ""; ?>>
                <span class="dd_tooltip" data-toggle="tooltip" data-placement="right" title="Enable Postal Code.">&#63;</span>
            <?php
                echo "<td><b>Select postal code</b>";
                echo '<input id="postalCode" text-align=left name="dd_settings[postal_code]" type="text" class="input-small" value="' . $options['postal_code'] . ' " autocomplete="off" />';
                echo "<div class='height'></div></td>";
                break;

            case 'dd_dash_select_disable_dates':
            ?>
                <input id="select_disable_date" type="checkbox" name="dd_settings[allow_disable_dates]" value="checked" <?php echo $options['allow_disable_dates']; ?>>
                <span class="dd_tooltip" data-toggle="tooltip" data-placement="right" title="Enable disable dates.">&#63;</span>
            <?php
                echo "<td><b>Enter number of disable dates </b>";
                echo '<input id="number_of_disable_dates" text-align=left name="dd_settings[number_disable_dates]" type="text" class="input-small" value="' . $options['number_disable_dates'] . ' " autocomplete="off" />';
                echo "<div class='height'></div></td>";
                break;


            case 'dd_dash_select_days':

            ?><input id="select_days" name="dd_settings[select_days_or_date]" type="radio" class="input-small validate_days_date" value="override_days" <?php if ($options['select_days_or_date'] == 'override_days') {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?> />
                <div class='height'></div>
            <?php
                break;

            case 'dd_dash_select_dates':

            ?><input id="select_dates" name="dd_settings[select_days_or_date]" type="radio" class="input-small validate_days_date" value="override_dates" <?php if ($options['select_days_or_date'] == 'override_dates') {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?> />
                <div class='height'></div>
            <?php
                break;

            case 'dd_dash_select_dates_range':

            ?><input id="select_dates_range" name="dd_settings[select_days_or_date]" type="radio" class="input-small validate_days_date" value="override_dates_range" <?php if ($options['select_days_or_date'] == 'override_dates_range') {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?> />
                <div class='height'></div>
            <?php
                break;

            case 'dd_dash_select_holiday':

            ?><input id="select_holiday" name="dd_holidays[select_days_or_date]" type="checkbox" class="input-small validate_days_date" value="override_holiday" <?php if ($holidays_options['select_days_or_date'] == 'override_holiday') {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                <div class='height'></div>
            <?php
                break;



            case 'dd_deliveryday_slot':
            ?>
                <div class="deliveryday_wrapper">
                    <div class="deliveryday_results">

                        <?php

                        if ($dates_range_options['deliveryday_slot'] == true) {
                            foreach ($dates_range_options['deliveryday_slot'] as $value) {
                        ?><input type="text" name="dd_dates_ranges[deliveryday_slot][]" class="deliveryday" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                                    }
                                                                                                                                                } else {
                                                                                                                                                        ?><input type="text" name="dd_dates_ranges[deliveryday_slot][]" class="deliveryday" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                                    }


                                                                                                                                                        ?>
                    </div>

                    <div class="holiday_buttons">
                        <span class="deliveryday_clone">Add</span>
                        <span class="deliveryday_remove">Cancel</span>

                    </div>

                </div>
            <?php
                break;


            case 'dd_multi_time_slot':

            ?>
                <div class="wrapper">
                    <div class="results">
                        <?php
                        if ($days_options['datetimes'] == true) {
                            foreach ($days_options['datetimes'] as $value) {
                        ?><input type="text" name="dd_days[datetimes][]" class="datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                        ?><input type="text" name="dd_days[datetimes][]" class="datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                    }


                                                                                                                                        ?>
                    </div>
                    <div class="buttons">

                        <span class="clone">Add</span>
                        <span class="remove">Cancel</span>


                    </div>
                </div>
            <?php
                break;


            case 'dd_days_multi_time_slot':

            ?>
                <div class="days_wrapper">
                    <div class="days_results">
                        <?php
                        if ($days_options['days_datetimes'] == true) {
                            foreach ($days_options['days_datetimes'] as $value) {
                        ?><input type="text" name="dd_days[days_datetimes][]" class="days_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                                ?><input type="text" name="dd_days[days_datetimes][]" class="days_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                            }


                                                                                                                                                ?>
                    </div>
                    <div class="days_buttons">

                        <span class="days_clone">Add</span>
                        <span class="days_remove">Cancel</span>


                    </div>
                </div>
            <?php
                break;

            case 'dd_dates_multi_time_slot':

            ?>
                <div class="dates_wrapper">
                    <div class="dates_results">
                        <?php
                        if ($dates_options['dates_datetimes'] == true) {
                            foreach ($dates_options['dates_datetimes'] as $value) {
                        ?><input type="text" name="dd_dates[dates_datetimes][]" class="dates_datetimes" value="<?php echo $value; ?>" autocomplete="off" />
                        <?php
                            }
                        } else {
                                ?><input type="text" name="dd_dates[dates_datetimes][]" class="dates_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                            }
                        ?>
                    </div>
                    <div class="dates_buttons">

                        <span class="dates_clone">Add</span>
                        <span class="dates_remove">Cancel</span>


                    </div>
                </div>
            <?php
                break;

            case 'dd_daterange_multi_time_slot':

            ?>
                <div class="daterange_wrapper">
                    <div class="daterange_results">
                        <?php
                        if ($dates_range_options['daterange_datetimes'] == true) {
                            foreach ($dates_range_options['daterange_datetimes'] as $value) {
                        ?><input type="text" name="dd_dates_ranges[daterange_datetimes][]" class="daterange_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                                                }
                                                                                                                                                            } else {
                                                                                                                                                                    ?><input type="text" name="dd_dates_ranges[daterange_datetimes][]" class="daterange_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                                                }


                                                                                                                                                                    ?>
                    </div>
                    <div class="daterange_buttons">

                        <span class="_">Add</span>
                        <span class="_daterange_remove">Cancel</span>


                    </div>
                </div>
            <?php
                break;
            case 'dd_weekday_daterange_multi_time_slot':

                   
                        break;    

            case 'dd_holiday_slot':
            ?>
                <div class="holiday_wrapper">
                    <div class="holiday_results">
                        <?php
                        if ($holidays_options['holiday'] == true) {
                            foreach ($holidays_options['holiday'] as $value) {
                        ?><input type="text" name="dd_holidays[holiday][]" class="holiday" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                        ?><input type="text" name="dd_holidays[holiday][]" class="holiday" value="<?php echo (isset($value))?$value:''; ?>" autocomplete="off" /><?php
                                                                                                                                    }


                                                                                                                                        ?>
                    </div>
                    <div class="holiday_buttons">
                        <span class="holiday_clone">Add</span>
                        <span class="holiday_remove">Cancel</span>

                    </div>
                </div>
            <?php
                break;

            case 'dd_holiday_slot_recurring':
            ?>
                <input type="checkbox" name="dd_settings[allow_recurring]" value="checked" <?php echo $options['allow_recurring']; ?>>

                <span class="dd_tooltip" data-toggle="tooltip" data-placement="right" title="Disable holidays for all upcoming years.">&#63;</span>
            <?php
                break;

            case 'dd_notification_mail':
            ?>
                <input type="checkbox" name="dd_settings[allow_notification_mail]" value="checked" <?php echo $options['allow_notification_mail']; ?>>
            <?php
                break;

            case 'dd_display_cart_page':
            ?>
                <input type="checkbox" name="dd_settings[allow_cart_page]" value="checked" <?php echo $options['allow_cart_page']; ?>>
                <span class="dd_tooltip" data-toggle="tooltip" data-placement="right" title="Display Delivery Date as a text on cart and checkout page.">&#63;</span>
            <?php
                break;

            case 'dd_display_product_page':
            ?>
                <input type="checkbox" name="dd_settings[allow_product_page]" value="checked" <?php echo $options['allow_product_page']; ?>>
                <span class="dd_tooltip" data-toggle="tooltip" data-placement="right" title="Enable Delivery Calender on Product page.">&#63;</span>
            <?php
                break;

            case 'dd_display_shop_page':
            ?>
                <input type="checkbox" name="dd_settings[allow_shop_page]" value="checked" <?php echo (array_key_exists('allow_shop_page',$options))?$options['allow_shop_page']:""; ?>>
                <span class="dd_tooltip" data-toggle="tooltip" data-placement="right" title="Enable Delivery Calender on Shop page.">&#63;</span>
            <?php
                break;

            case 'dd_notification_mail_time_before_delivery':
            ?>
                <input type="text" name="dd_settings[allow_notification_mail_time_before_delivery]" value="<?php echo $options['allow_notification_mail_time_before_delivery']; ?>">
        <?php
                break;
        }
    }


    /**
     * Callback to print section descriptions called upon add settings section.
     *
     * @since    1.0.0
     */
    public function dld_settings_section_cb($args)
    {

        foreach ($this->dd_settings['sections'] as $section) {

            if ($args['id'] == $section['id']) {
                echo __($section['desc'], 'dd-dash');
                break;
            }
        }
    }

    /**
     * Callback for plugin's settings page called upon add menu page.
     *
     * @since    1.0.0
     */
    public function dld_settings_page_cb()
    {
        $options = get_option('dd_settings');
        $options1 = get_option('dd_settings');

        ?>

        <div class="delivery_tab_header">
            <div class="row" style="width: 100%;">
                <div class="col-md-8 ">
                    <div class=" wqc_main_header col-md-2 ">
                        <img class="QB_icon" src="<?php echo plugin_dir_url(__FILE__) . "../woocommerce-delivery-schedular/admin/images/delivery_icon.jpg"; ?>">
                        <!-- <i class="fa fa-sitemap QB_icon"></i> -->
                    </div>
                    <div class="col-md-10">
                        <div class="delivery_header"> Fine Woocommerce Delivery Schedular </div>
                        <p>
                            As an administrator, you can provide delivery days or dates or date range and time slot to the customer and then customer can select any delivery day or date or date range and time slot that you provide. Administrator has a rights to enable or disable delivery days or dates or date range for all products or particular product.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="delivery_padding">
                        <!-- Connection Status Start -->
                        <div class="col-md-6">

                            <div class="delivery_tutorial" onclick="window.open('https://www.youtube.com/watch?v=2rKZbWf557s');"> <i class="fab fa-youtube-square" aria-hidden="true"></i>
                                <div>Tutorial</div>
                            </div>
                        </div>
                        <!-- Connection Status End -->
                        <!-- User Guide and Support Start -->
                        <div class="col-md-6">
                            <div class="delivery_info" onclick="window.open('https://kostali.com/docs/woocommerce-delivery-schedular/');"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                <div>User Guide</div>
                            </div>
                            <div class="delivery_info" onclick="window.open('https://kostali.com/connect-with-kostali/');"> <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <div>Support</div>
                            </div>
                        </div>
                    </div>
                    <!-- User Guide and Support End -->

                </div>
            </div>
        </div>
        <div class="tabs">
            <ul class="tabs-list">
                <li>
                    <a href="#Delivery_Days" class="link_class active"><i class=" delivery_fa_icon fas fa-calendar-day" aria-hidden="true"></i>
                        <div>Delivery Days</div>
                    </a></li>
                <li><a href="#Delivery_Dates" class="link_class"><i class=" delivery_fa_icon fas fa-calendar-alt"></i>
                        <div>Delivery Dates</div>
                    </a></li>
                <li><a href="#Delivery_Dates_Range" class="link_class"><i class="delivery_fa_icon fas fa-calendar-week"></i>
                        <div>Delivery Dates Range</div>
                    </a></li>
                <li><a href="#Week_Days_Timeslots" class="link_class"><i class="delivery_fa_icon fas fa-calendar-week"></i>
                        <div>Week Days Timeslots</div>
                    </a></li>    
                <li><a href="#Holidays" class="link_class"><i class="delivery_fa_icon far fa-calendar-times"></i>
                        <div>Holidays Dates</div>
                    </a></li>
                <li><a href="#Settings" class="link_class"> <i class=" delivery_fa_icon fa fa-cogs" aria-hidden="true"></i>
                        <div>Settings</div>
                    </a></li>
                <li><a href="#Support" class="link_class"><i class="delivery_fa_icon fa fa-question" aria-hidden="true"></i>
                        <div>Support</div>
                    </a></li>
            </ul>

            <div id="Delivery_Days" class="active tab">
                <h3><i class="delivery_fa_icon fas fa-calendar-day" aria-hidden="true"></i><span></span>
                    Delivery Days</h3>
                <div class='wrap'>

                    <form action="options.php" method='post'>

                        <?php
                        esc_html(get_admin_page_title());

                        settings_fields('dd-dashboard1');
                        do_settings_sections('dd-dashboard1');
                        submit_button(); ?>
                    </form>

                </div>

            </div>
            <div id="Delivery_Dates" class="tab">
                <h3><i class=" delivery_fa_icon fas fa-calendar-alt"></i><span></span>
                    Delivery Dates</h3>
                <div class='wrap'>
                    <form action='options.php' method='post'>
                        <?php
                        esc_html(get_admin_page_title());

                        settings_fields('dd-dashboard2');
                        do_settings_sections('dd-dashboard2');
                        submit_button(); ?>
                    </form>
                </div>
            </div>
            <div id="Delivery_Dates_Range" class="tab">
                <h3><i class="delivery_fa_icon fas fa-calendar-week"></i><span></span>
                    Delivery Dates Range</h3>
                <div class='wrap'>
                    <form action='options.php' method='post'>
                        <?php
                        esc_html(get_admin_page_title());

                        settings_fields('dd-dashboard3');
                        do_settings_sections('dd-dashboard3');
                        submit_button(); ?>
                    </form>
                </div>

            </div>

            <div id="Week_Days_Timeslots" class="tab">
                <h3><i class="delivery_fa_icon fas fa-calendar-week"></i><span></span>
                    Week Days Timeslots</h3>
                <div class='wrap'>
                    <form action='options.php' method='post'>
                        <?php esc_html(get_admin_page_title()); ?>

                        <?php 
                             settings_fields('dd-dashboard5');
                             do_settings_sections('dd-dashboard5');
                        ?>
                       <?php submit_button(); ?>
                    </form>
                </div>

            </div>

            <div id="Holidays" class="tab">
                <h3>
                    <i class="delivery_fa_icon far fa-calendar-times"></i><span></span>
                    Holidays</h3>
                <div class='wrap'>
                    <form action='options.php' method='post'>
                        <?php
                        esc_html(get_admin_page_title());

                        settings_fields('dd-dashboard4');
                        do_settings_sections('dd-dashboard4');
                        submit_button(); ?>
                    </form>
                </div>

            </div>



            <div id="Settings" class="tab">
                <h3>
                    <i class=" delivery_fa_icon fa fa-cogs" aria-hidden="true"></i><span></span>
                    Settings</h3>
                <div class='wrap'>
                    <form action='options.php' method='post'>
                        <?php
                        esc_html(get_admin_page_title());

                        settings_fields('dd-dashboard');
                        do_settings_sections('dd-dashboard');
                        submit_button();
                        ?>
                    </form>
                </div>

            </div>

            <div id="Support" class="tab">

                <h3><span>&#63;</span> Need Support </h3>
                <div class="col-md-8">
                    <iframe width="100%" height="350" src="https://www.youtube.com/embed/2rKZbWf557s">
                    </iframe></div>
                <div class="col-md-4">
                    <div class="support_btn" onclick="window.open('https://kostali.com/connect-with-kostali/');">Contact Us</div>
                    <div class="support_btn" onclick="window.open('https://kostali.com/docs/woocommerce-delivery-schedular/');">User Guide</div>
                    <div class="support_btn" onclick="window.open('https://kostali.com/');">About Us</div>
                </div>
                <div class="col-md-12">
                    <div class=tab-heading>
                        <h3><span>&#63;</span> FAQ </h3>
                    </div>

                    <div class="step-box">
                        <!-- <div class="setting_heading titlecolor">
            <h4>FAQ</h4>
         </div>
         <hr /> -->
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            <strong>1. How to setup the delivery dates plugin for all products?</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        Ans: To configure the the delivery date plugin for all products you need to go into your Admin Panel > Delivery Dates Dashboards > You can enable and disable delivery dates,days and dates range.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            <strong>2. How to setup the delivery dates plugin for Single Products?</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        Ans: To configure the the delivery date plugin for all products you need to go into your producteditor > Delivery date metabox > Click on override global setting and select days or dates or dates range.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            <strong>3.Does plugin require any special Permission?</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        Ans: No special permissions required.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            <strong>4. Does plugin depend on any another plugin?</strong>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
                                        Ans: Yes, Woocommerce plugin is required.
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- panel-group -->
                    </div>
                </div>

            </div>
        </div>


    <?php


    }

    public function dld_add_meta_box()
    {

        add_meta_box(
            'del_date_meta_box',
            __('Delivery Date & Timeslots', 'del_date'),
            [$this, 'dld_add_custom_content_meta_box'],
            'product',
            'normal',
            'default'
        );
    }

    //  Custom metabox content in admin product pages
    public function dld_add_custom_content_meta_box($post)
    {

        $prefix = '_del_date_'; // global $prefix;
        $custom_date = get_post_meta($post->ID, $prefix . 'custom_date', true) ? get_post_meta($post->ID, $prefix . 'custom_date', true) : '';



        $mul_sel_date = get_post_meta($post->ID, $prefix . 'mul_sel_date', true) ? get_post_meta($post->ID, $prefix . 'mul_sel_date', true) : '';

        $Delivery_dates_days = get_post_meta($post->ID, $prefix . 'my_key', true) ? get_post_meta($post->ID, $prefix . 'my_key', true) : '';

        $Override_Delivery_dates = get_post_meta($post->ID, $prefix . 'Override_Delivery_dates', true) ? get_post_meta($post->ID, $prefix . 'Override_Delivery_dates', true) : '';

        // get time slot value start
        $mul_datetimes = get_post_meta($post->ID, $prefix . 'mul_datetimes', true) ? get_post_meta($post->ID, $prefix . 'mul_datetimes', true) : '';

        $prefix = '_del_date_';

        $datetimes_days_slot = get_post_meta($post->ID, $prefix . 'datetimes_days', true);

        $datetimes_dates_slot = get_post_meta($post->ID, $prefix . 'datetimes_dates', true);
        $datetimes_daterange = get_post_meta($post->ID, $prefix . 'datetimes_daterange', true);
       

        $product_monday_timeslots = get_post_meta($post->ID, $prefix . 'dd_monday_timeslots', true    );
        $product_tuesday_timeslots = get_post_meta($post->ID, $prefix . 'dd_tuesday_timeslots', true   );
        $product_wednesday_timeslots = get_post_meta($post->ID, $prefix . 'dd_wednesday_timeslots', true );
        $product_thursday_timeslots = get_post_meta($post->ID, $prefix . 'dd_thursday_timeslots', true  );
        $product_friday_timeslots = get_post_meta($post->ID, $prefix . 'dd_friday_timeslots', true    );
        $product_saturday_timeslots = get_post_meta($post->ID, $prefix . 'dd_saturday_timeslots', true  );
        $product_sunday_timeslots = get_post_meta($post->ID, $prefix . 'dd_sunday_timeslots', true    );

        // get time slot value end

        // get holiday slot value start
        $mul_holiday = get_post_meta($post->ID, $prefix . 'mul_holiday', true) ? get_post_meta($post->ID, $prefix . 'mul_holiday', true) : '';

        $mul_holiday_holidays_check = get_post_meta($post->ID, $prefix . 'mul_holiday_holidays_check', true) ? get_post_meta($post->ID, $prefix . 'mul_holiday_holidays_check', true) : '';
        $mul_Deliveryday_range = get_post_meta($post->ID, $prefix . 'mul_Deliveryday_range', true);

        $Override_recurring_slot = get_post_meta($post->ID, $prefix . 'Override_recurring_slot', true) ? get_post_meta($post->ID, $prefix . 'Override_recurring_slot', true) : '';
        // get holiday slot value end

        // allow notification mail start
        $allow_notification_mail_single_product = get_post_meta($post->ID, $prefix . 'allow_notification_mail_single_product', true) ? get_post_meta($post->ID, $prefix . 'allow_notification_mail_single_product', true) : '';

        $allow_notification_mail_time_before_delivery = get_post_meta($post->ID, $prefix . 'allow_notification_mail_time_before_delivery', true) ? get_post_meta($post->ID, $prefix . 'allow_notification_mail_time_before_delivery', true) : '';

        // allow notification mail end

        $args['textarea_rows'] = 6;



    ?>
        <div class="parentDiv">
            <div class="tabsetting">
                <nav>
                    <!-- <a class="pro_link_class">Override Global Settings</a> -->
                    <a class="pro_link_class">Override Global Settings</a>
                    <a class="pro_link_class">Delivery Days</a>
                    <a class="pro_link_class">Delivery Dates</a>
                    <a class="pro_link_class">Daily Delivery Timeslots</a>
                    <a class="pro_link_class">Delivery Dates Range</a>
                    <a class="pro_link_class">Holidays Dates</a>
                    <a class="pro_link_class">Settings tab</a>
                </nav>
                <div class="content">
                    <h4>Override Global Settings</h4>
                    <input type="checkbox" name="Override_Delivery_dates" value="Override_Delivery_dates" <?php checked($Override_Delivery_dates, 'Override_Delivery_dates'); ?> /> <?php echo esc_html__('Override Global Settings', 'Delivery-Date'); ?>

                </div>
                <div class="content">
                    <h4>Delivery Days</h4>
                    <?php
                    echo "<table>";

                    $custom_meta = get_post_meta($post->ID, $prefix . 'mul_sel_day', true);
                    $value = get_post_meta($post->ID, 'my_key', true);

                    ?>
                    <div class="height"></div>

                    <input type="checkbox" name="mul_sel_day[]" value="0" <?php echo (in_array('0', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Sunday', 'Delivery-Date'); ?>
                    <br>
                    <input type="checkbox" name="mul_sel_day[]" value="1" <?php echo (in_array('1', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Monday', 'Delivery-Date'); ?>
                    <br>
                    <input type="checkbox" name="mul_sel_day[]" value="2" <?php echo (in_array('2', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Tuesday', 'Delivery-Date'); ?><br>

                    <input type="checkbox" name="mul_sel_day[]" value="3" <?php echo (in_array('3', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Wednesday', 'Delivery-Date'); ?><br>
                    <input type="checkbox" name="mul_sel_day[]" value="4" <?php echo (in_array('4', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Thursday', 'Delivery-Date'); ?><br>

                    <input type="checkbox" name="mul_sel_day[]" value="5" <?php echo (in_array('5', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Friday', 'Delivery-Date'); ?><br>

                    <input type="checkbox" name="mul_sel_day[]" value="6" <?php echo (in_array('6', $custom_meta)) ? 'checked="checked"' : ''; ?> /> <?php echo esc_html__('Saturday', 'Delivery-Date'); ?><br>
                    <?php

                    echo "</table>";

                    echo '<input type="hidden" name="custom_product_field_nonce" value="' . wp_create_nonce() . '">';

                    ?>

                    <br>

                    <div class="mul_days_wrapper">
                        <div class="mul_days_results">
                            <?php
                            if ($datetimes_days_slot == true) {
                                foreach ($datetimes_days_slot as $value) {
                            ?><input type="text" name="mul_datetimes_days[]" class="mul_days_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                            }
                                                                                                                                        } else {

                                                                                                                                                ?><input type="text" name="mul_datetimes_days[]" class="mul_days_datetimes" value="" autocomplete="off" /><?php

                                                                                                                                        }
                                                                                                                            ?>
                        </div>
                        <div class="mul_days_buttons">
                            <span class="mul_days_clone">Add</span>
                            <span class="mul_days_remove">Cancel</span>

                        </div>
                    </div>
                </div>
                <div class="content">

                    <h4>Delivery Dates</h4>
                    <?php

                    echo  "<input id='datePick' name='mul_sel_date' type='text' value='$mul_sel_date' autocomplete='off' />";

                    echo "<div>";
                    echo "</div>"; ?>
                    <br>
                    <div class="mul_dates_wrapper">
                        <div class="mul_dates_results">
                            <?php
                            if ($datetimes_dates_slot == true) {
                                foreach ($datetimes_dates_slot as $value) {
                            ?><input type="text" name="mul_datetimes_dates[]" class="mul_dates_datetimes" value="<?php echo $value; ?>" autocomplete="off" />
                            <?php } } else {?>
                        <input type="text" name="mul_datetimes_dates[]" class="mul_dates_datetimes" value="" autocomplete="off" />
                        <?php } ?>
                        </div>
                        <div class="mul_dates_buttons">
                            <span class="mul_dates_clone">Add</span>
                            <span class="mul_dates_remove">Cancel</span>

                        </div>
                    </div>
                </div>
                <div class="content">
                <h4>Daily Delivery Timeslots</h4>
                    <br>
                    <?php $this->echo_dd_weekday_timeslot_fields('monday'); $this->echo_dd_weekday_timeslots('monday', $product_monday_timeslots); ?>
                    <?php $this->echo_dd_weekday_timeslot_fields('tuesday'); $this->echo_dd_weekday_timeslots('tuesday',$product_tuesday_timeslots); ?>
                    <?php $this->echo_dd_weekday_timeslot_fields('wednesday'); $this->echo_dd_weekday_timeslots('wednesday', $product_wednesday_timeslots); ?>
                    <?php $this->echo_dd_weekday_timeslot_fields('thursday'); $this->echo_dd_weekday_timeslots('thursday', $product_thursday_timeslots); ?>
                    <?php $this->echo_dd_weekday_timeslot_fields('friday'); $this->echo_dd_weekday_timeslots('friday', $product_friday_timeslots); ?>
                    <?php $this->echo_dd_weekday_timeslot_fields('saturday'); $this->echo_dd_weekday_timeslots('saturday', $product_saturday_timeslots); ?>
                    <?php $this->echo_dd_weekday_timeslot_fields('sunday'); $this->echo_dd_weekday_timeslots('sunday', $product_sunday_timeslots); ?>

                </div>
                <div class="content">
                    <h4>Delivery Dates Range</h4>
                    <br>
                    <div class="mul_wrapper_deliveryday">
                        <div class="mul_results_deliveryday">
                            <?php
                            if ($mul_Deliveryday_range == true) {
                                foreach ($mul_Deliveryday_range as $value) {
                            ?><input type="text" name="mul_delivery_daterange[]" class="mul_deliveryday" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                                ?><input type="text" name="mul_delivery_daterange[]" class="mul_deliveryday" value="" autocomplete="off" /><?php
                                                                                                                                        }
                                                                                                                            ?>
                        </div>
                        <div class="mul_buttons_deliveryday">
                            <span class="mul_clone_deliveryday">Add</span>
                            <span class="mul_remove_deliveryday">Cancel</span>
                        </div>
                    </div>
                    <br>
                    <div class="mul_datesrange_wrapper">
                        <div class="mul_datesrange_results">
                            <?php
                            if ($datetimes_daterange == true) {
                                foreach ($datetimes_daterange as $value) {
                            ?><input type="text" name="mul_datetimes_daterange[]" class="mul_datesrange_datetimes" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                                        }
                                                                                                                                                    } else {

                                                                                                                                                            ?><input type="text" name="mul_datetimes_daterange[]" class="mul_datesrange_datetimes" value="" autocomplete="off" /><?php

                                                                                                                                                    }
                                                                                                                                    ?>
                        </div>
                        <div class="mul_datesrange_buttons">
                            <span class="mul_datesrange_clone">Add</span>
                            <span class="mul_datesrange_remove">Cancel</span>

                        </div>
                    </div>
                </div>
                <div class="content">
                    <h4>Holidays Dates</h4>
                    <br>
                    <input type="checkbox" name="holidays_check" value="checked" <?php checked($mul_holiday_holidays_check, 'checked'); ?>> <?php echo __('Select Holiday Slot', 'Delivery-Date'); ?> <br><br>
                    <div class="mul_wrapper_holiday">
                        <div class="mul_results_holiday">
                            <?php
                            if ($mul_holiday == true) {
                                foreach ($mul_holiday as $value) {
                            ?><input type="text" name="mul_holiday[]" class="mul_holiday" value="<?php echo $value; ?>" autocomplete="off" /><?php
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                    ?><input type="text" name="mul_holiday[]" class="mul_holiday" value="" autocomplete="off" /><?php
                                                                                                                            }
                                                                                                            ?>
                        </div>
                        <div class="mul_buttons_holiday">
                            <span class="mul_clone_holiday">Add</span>
                            <span class="mul_remove_holiday">Cancel</span>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <h4>Settings</h4>
                    <input type="radio" name="Delivery_dates_days" value="Delivery_Days" <?php checked($Delivery_dates_days, 'Delivery_Days'); ?>> <?php echo esc_html__('Enable Delivery Days', 'Delivery-Date'); ?> <br>
                    <div class="height"></div>
                    <input type="radio" name="Delivery_dates_days" value="Delivery_dates" <?php checked($Delivery_dates_days, 'Delivery_dates'); ?>> <?php echo esc_html__('Enable Delivery Date', 'Delivery-Date'); ?>
                    <div class="height"></div>
                    <input type="radio" name="Delivery_dates_days" value="Delivery_Deliveryday" <?php checked($Delivery_dates_days, 'Delivery_Deliveryday'); ?>> <?php echo __('Enable Delivery Date Range', 'Delivery_Deliveryday'); ?> <br>


                    <!--  -->

                    <!-- recurring holiday slot end -->

                    <!-- Allow Notification via mail start -->
                    <br>

                    <input type="checkbox" name="allow_notification_mail_single_product" value="allow_notification_mail_single_product" <?php checked($allow_notification_mail_single_product, 'allow_notification_mail_single_product'); ?> /> <?php echo __('Send Notification Mail Before Delivery', 'Delivery-Date'); ?>


                    <br>
                    <br>
                    <?php echo __('Send Notification Mail Before Delivery Hours', 'Delivery-Date'); ?><br> <input type="text" name="allow_notification_mail_time_before_delivery" value="<?php echo $allow_notification_mail_time_before_delivery; ?>" />
                </div>
            </div>
        </div>

        <!-- Allow Notification via mail end -->

<?php

    }

    //Save the data of the Meta field
    public function dld_save_meta_box_fields()
    {

        global $post;

        $prefix = '_del_date_'; // global $prefix;

        // We need to verify this with the proper authorization (security stuff).
        // Check if our nonce is set.
        if (!isset($_POST['custom_product_field_nonce'])) {
            return $post_id;
        }
        $nonce = $_REQUEST['custom_product_field_nonce'];

        //Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce)) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        if ('product' == sanitize_text_field($_POST['post_type'])) {
            if (!current_user_can('edit_product', $post_id))
                return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }

        update_post_meta($post->ID, $prefix . 'custom_date', wp_kses_post(sanitize_text_field($_POST['custom_date'])));

    
        update_post_meta($post->ID, $prefix . 'datetimes_days', $_POST['mul_datetimes_days']);
        update_post_meta($post->ID, $prefix . 'datetimes_dates', $_POST['mul_datetimes_dates']);
        update_post_meta($post->ID, $prefix . 'datetimes_daterange', $_POST['mul_datetimes_daterange']);
        update_post_meta($post->ID, $prefix . 'dd_monday_timeslots', $_POST['dd_monday_timeslots']       );
        update_post_meta($post->ID, $prefix . 'dd_tuesday_timeslots', $_POST['dd_tuesday_timeslots']     );
        update_post_meta($post->ID, $prefix . 'dd_wednesday_timeslots', $_POST['dd_wednesday_timeslots'] );
        update_post_meta($post->ID, $prefix . 'dd_thursday_timeslots', $_POST['dd_thursday_timeslots']   );
        update_post_meta($post->ID, $prefix . 'dd_friday_timeslots', $_POST['dd_friday_timeslots']       );
        update_post_meta($post->ID, $prefix . 'dd_saturday_timeslots', $_POST['dd_saturday_timeslots']   );
        update_post_meta($post->ID, $prefix . 'dd_sunday_timeslots', $_POST['dd_sunday_timeslots']       );
        

        $save =  get_post_meta($post->ID, $prefix . 'datetimes', true);


        update_post_meta($post->ID, $prefix . 'mul_sel_date', wp_kses_post(sanitize_text_field($_POST['mul_sel_date'])));

        update_post_meta($post->ID, $prefix . 'mul_sel_day', $_POST['mul_sel_day']);

        // holiday slot start

        update_post_meta($post->ID, $prefix . 'mul_Deliveryday_range', $_POST['mul_delivery_daterange']);

        update_post_meta($post->ID, $prefix . 'mul_holiday', $_POST['mul_holiday']);
        update_post_meta($post->ID, $prefix . 'mul_holiday_holidays_check', $_POST['holidays_check']);

        update_post_meta($post->ID, $prefix . 'Override_recurring_slot', wp_kses_post($_POST['Override_recurring_slot']));

        // holiday slot end
        update_post_meta($post->ID, $prefix . 'my_key', wp_kses_post(sanitize_text_field($_POST['Delivery_dates_days'])));
        update_post_meta($post->ID, $prefix . 'Override_Delivery_dates', wp_kses_post(sanitize_text_field($_POST['Override_Delivery_dates'])));

        // update time slot start


        // update time slot end
        // allow notification mail start
        update_post_meta($post->ID, $prefix . 'allow_notification_mail_single_product', wp_kses_post($_POST['allow_notification_mail_single_product']));
        update_post_meta($post->ID, $prefix . 'allow_notification_mail_time_before_delivery', wp_kses_post($_POST['allow_notification_mail_time_before_delivery']));
        // allow notification mail end 


        $new_meta_value = (isset($_POST['Delivery_dates_days']) ? sanitize_html_class($_POST['Delivery_dates_days']) : '');
    }

    public function dld_save_dashboard_timeslots_options(){
        error_reporting('save option:');
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($weekdays as $day){
            $field = 'dd_' . $day . '_timeslots[]';
            if(array_key_exists($field, $_POST)){
                error_log('option: '. $field);
                update_option($field, $_POST[$field], true);
            }
        }
    }
}
?>