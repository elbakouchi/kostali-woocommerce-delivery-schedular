<?php

/**
 * AJAX event handler class
 *
 * Includes functions used across both the public-facing side of the site and the admin area. 
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Delivery-Date
 * @subpackage Delivery-Date/includes
 */

require_once dirname(__FILE__) . '../woocommerce-delivery-schedular/includes/class-delivery-date-ajax.php';

class Custom_Delivery_Date_AJAX extends Delivery_Date_AJAX
{




	public function dld_as_read()
	{
		$id = $_POST['id'];
		if (empty($id)) {
			$id = get_option('custom_product_id');
		}
		$multi_select_date = get_post_meta($id, '_del_date_mul_sel_date', true) ? get_post_meta($id, '_del_date_mul_sel_date', true) : '';

		$multi_select_daterange = get_post_meta($id, '_del_date_mul_Deliveryday_range', true) ? get_post_meta($id, '_del_date_mul_Deliveryday_range', true) : '';

		$multi_select_day = get_post_meta($id, '_del_date_mul_sel_day', true) ? get_post_meta($id, '_del_date_mul_sel_day', true) : '';
		//  holiday slot start
		$multi_select_holdidays = get_post_meta($id, '_del_date_mul_holiday', true) ? get_post_meta($id, '_del_date_mul_holiday', true) : '';
		$Override_recurring_slot = get_post_meta($id, '_del_date_Override_recurring_slot', true) ? get_post_meta($id, '_del_date_Override_recurring_slot', true) : '';

		//  holiday slot end
		$multi_global_setting = get_option('dd_settings');
		$multi_globaldates_setting = get_option('dd_dates');
		$multi_globaldays_setting = get_option('dd_days');
		$multi_globaldatesranges_setting = get_option('dd_dates_ranges');
		$multi_globalholidays_setting = get_option('dd_holidays');
		$del_date = get_post_meta($id, '_del_date_Override_Delivery_dates', true);
		if ($del_date == 'Override_Delivery_dates') {

			// holiday response start for single products

			$array = array();

			foreach ($multi_select_holdidays as $value) {

				$start_end_date = explode(" - ", $value);

				$updated_start_date = str_replace('/', '-', $start_end_date[0]);
				$updated_end_date = str_replace('/', '-', $start_end_date[1]);

				$start_date = $updated_start_date;
				$end__date = $updated_end_date;

				$apply_start_date = strtotime($start_date);
				$apply_end_date = strtotime($end__date);

				for (
					$currentDate = $apply_start_date;
					$currentDate <= $apply_end_date;
					$currentDate += (86400)
				) {

					$Store = date('d-m-Y', $currentDate);
					$array[] = $Store;
				}
			}


			$array1 = array();
			foreach ($multi_select_daterange as $value) {

				$start_end_date = explode(" - ", $value);

				$updated_start_date = str_replace('/', '-', $start_end_date[0]);
				$updated_end_date = str_replace('/', '-', $start_end_date[1]);

				$start_date = $updated_start_date;
				$end__date = $updated_end_date;

				$apply_start_date = strtotime($start_date);
				$apply_end_date = strtotime($end__date);

				for (
					$currentDate = $apply_start_date;
					$currentDate <= $apply_end_date;
					$currentDate += (86400)
				) {

					$Store = date('d-m-Y', $currentDate);
					$array1[] = $Store;
				}
			}


			// holiday response end for single products

			$value = get_post_meta($id, '_del_date_my_key', true);
			$prefix = '_del_date_';
			$mul_holiday_holidays_check = get_post_meta($id, $prefix . 'mul_holiday_holidays_check', true) ? get_post_meta($id, $prefix . 'mul_holiday_holidays_check', true) : '';
			if ($value == "Delivery_dates") {
				if ($mul_holiday_holidays_check == "checked") {
					$arr = explode(', ', trim($multi_select_date));
					$response2 = array(
						'override' => "override_dates",
						'disable_dates' => $arr,
						'holiday_check' => $mul_holiday_holidays_check,
						'holiday_slots' => $array,
						'allow_recurring' => $multi_global_setting['allow_recurring'],
					);
					echo json_encode($response2);
				} else {
					$arr = explode(', ', trim($multi_select_date));
					echo json_encode($arr);
				}
			} elseif ($value == "Delivery_Days") {
				if ($mul_holiday_holidays_check == "checked") {
					$response1 = array(
						'override' => "override_days",
						'avail_days' => $multi_select_day,
						'holiday_check' => $mul_holiday_holidays_check,
						'holiday_slots' => $array,
						'allow_recurring' => $multi_global_setting['allow_recurring'],
					);
					echo json_encode($response1);
				} else {
					$response = array(
						'override' => "override_days",
						'avail_days' => $multi_select_day,
						'allow_recurring' => $multi_global_setting['allow_recurring'],
					);
					echo json_encode($response);
				}
			} elseif ($value == "Delivery_Holiday" && $Override_recurring_slot == 'Override_recurring_slot') {
				echo json_encode($array);
			} elseif ($value == "Delivery_Holiday") {
				$response = array(
					'override' => "override_holiday",
					'holiday_slots' => $array,
					'allow_recurring' => $multi_global_setting['allow_recurring'],
				);
				echo json_encode($response);
			} else if ($value == "Delivery_Deliveryday") {
				if ($mul_holiday_holidays_check == "checked") {
					$response3 = array(
						'override' => "override_dates_range",
						'disable_dates_range' => $array1,
						'holiday_check' => $mul_holiday_holidays_check,
						'holiday_slots' => $array,
						'allow_recurring' => $multi_global_setting['allow_recurring'],
					);
					echo json_encode($response3);
				} else {
					echo json_encode($array1);
				}
			}
		} else {
			$array = array();

			foreach ($multi_globalholidays_setting['holiday'] as $value) {

				$start_end_date = explode(" - ", $value);

				$updated_start_date = str_replace('/', '-', $start_end_date[0]);
				$updated_end_date = str_replace('/', '-', $start_end_date[1]);

				$start_date = $updated_start_date;
				$end__date = $updated_end_date;

				$apply_start_date = strtotime($start_date);
				$apply_end_date = strtotime($end__date);

				for (
					$currentDate = $apply_start_date;
					$currentDate <= $apply_end_date;
					$currentDate += (86400)
				) {

					$Store = date('d-m-Y', $currentDate);
					$array[] = $Store;
				}
			}
			$array1 = array();

			foreach ($multi_globaldatesranges_setting['deliveryday_slot'] as $value) {
				$start_end_date = explode(" - ", $value);

				$updated_start_date = str_replace('/', '-', $start_end_date[0]);
				$updated_end_date = str_replace('/', '-', $start_end_date[1]);

				$start_date = $updated_start_date;
				$end__date = $updated_end_date;

				$apply_start_date = strtotime($start_date);
				$apply_end_date = strtotime($end__date);

				for (
					$currentDate = $apply_start_date;
					$currentDate <= $apply_end_date;
					$currentDate += (86400)
				) {

					$Store = date('d-m-Y', $currentDate);
					$array1[] = $Store;
				}
			}

			// holiday response end for all products

			$multi_select_date = $multi_globaldates_setting['disable_dates'];
			$avail_days = $multi_globaldays_setting['avail_days'];

			$disable_dates = explode(', ', trim($multi_select_date));
			if ($multi_globalholidays_setting['select_days_or_date'] == "override_holiday") {
				$response = array(
					'override' => $multi_global_setting['select_days_or_date'],
					'holiday_check' => 'checked',
					'disable_dates' => $disable_dates,
					'disable_dates_range' => $array1,
					'avail_days' => $avail_days,

					'holiday_slots' => $array,
					'allow_recurring' => $multi_global_setting['allow_recurring'],
				);

				echo json_encode($response);
			} else {
				$response = array(
					'override' => $multi_global_setting['select_days_or_date'],
					'disable_dates' => $disable_dates,
					'disable_dates_range' => $array1,
					'avail_days' => $avail_days,
					'holiday_slots' => $array,
					'allow_recurring' => $multi_global_setting['allow_recurring'],
				);

				echo json_encode($response);
			}
		}
	}

	public function dld_product_id_act()
	{

		$product_ids = get_option('custom_product_id');
		print_r($product_ids);
	}

	public function dld_mark_message_as_read()
	{

		$message_id = sanitize_text_field($_REQUEST['message_id']);
		update_option('delivery_date', $message_id);
		$option = get_option('delivery_date');
		print_r($option);
	}
}
