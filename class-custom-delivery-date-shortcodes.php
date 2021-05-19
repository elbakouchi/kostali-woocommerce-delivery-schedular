<?php

/**
 * Plugin shortcodes
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Delivery-Date
 * @subpackage Delivery-Date/includes
 */

class Custom_Delivery_Date_Shortcodes
{

	/**
	 * Init shortcodes.
	 */
	public static function init()
	{

		$shortcodes = array(
			'calender_p'	=> __CLASS__ . '::dld_cal_so',
			//'calender_shop' => __CLASS__ . '::dld_cal_so1',
		);

		foreach ($shortcodes as $shortcode => $function) {
			add_shortcode(apply_filters("{$shortcode}_shortcode_tagsss", $shortcode), $function);
		}
	}

	public static function dld_cal_so()
	{
		echo "<table><tbody>";
		$options = get_option('dd_settings');
		if (array_key_exists('allow_postal_code', $options) && $options['allow_postal_code'] == 'checked') {

			echo "<tr class='post_code_section'>";
			echo "<td class='postal_code'>" . esc_html__('Postal Code', 'Postal-Code') . "</td>";
			echo "<td class='input_post_code'><input type='text' required class='class_post_code' name='post_code' value=''></td>";
?>
			<div id="myDIV">
				<span class="" id="msg"></span>

			</div>
			<?php
			global $product;
			$id = $product->get_id();
			echo "<input type='hidden' id='postal-product-id' value='" . $id . "'>";
			echo "</tr>";

			echo "<tr class='calendar_section'>";
			echo "<td class='Delivery_d'>" . esc_html__('Delivery Date', 'Delivery-Date') . "</td>";

			echo "<td class='input-date'><input type='text' disabled required class='to-date' name='custom_date' value='' autocomplete='off'></td>";
			echo "<input name='pro' type='hidden' id='transferInput'>";
			global $product;
			$id = $product->get_id();
			echo "<input type='hidden' id='time-slot-user' value='" . $id . "'>";
			echo '<script>let productId = "' . $id . '" </script>';
			?>

			<script language='javascript'>
				jQuery("document").ready(function() {
					var product_id = (jQuery('.fp_prime_value').data('productid') !== undefined) ? jQuery('.fp_prime_value').data('productid') : productId;

					//		var product_id = jQuery('.fp_prime_value').data('productid');

					var jsdata = product_id;
					jQuery('#transferInput').val(jsdata);
					jQuery.ajax({
						type: 'POST',

						url: my_ajax_object.ajax_url,
						data: {
							action: 'dld_as_read',
							dataType: 'json',
							// add your parameters here
							id: jQuery('#transferInput').val()
						},
						success: function(output) {


							var newStr = output.slice(0, output.length - 1);
							var newStr_over = output.slice(0, output.length - 1);

							strdates = newStr.substring(newStr.indexOf('[') + 0);
							before_split_override = newStr.substring(newStr.indexOf(':"') + 2);

							split_override = before_split_override.split('",');
							try{
								var parsedTest_holidays = JSON.parse(newStr_over);
								
							}catch(e){
								var parsedTest_holidays = []
							}
							
							var check_holiday = parsedTest_holidays['holiday_check'];
							var type_delivery = newStr.substr(0, newStr.indexOf('['));
							var type_deliverys = type_delivery.trim();

							jQuery(function() {

								var to_chk_date = 'Delivery_dates';
								var to_chk_override_dates = 'override_dates';
								var to_chk_override_days = 'override_days';
								var to_chk_override_holiday = 'override_holiday';
								var to_chk_override_dates_range = 'override_dates_range';
								var to_check_single_pro_holiday = 'Delivery_Holiday';

								function enableAllTheseDays(date) {

									try{

										var obj = JSON.parse(strdates);
									}catch(e){
										var obj = []
									}
									var enableDays = obj;
									jQuery.each(enableDays, function(id, val) {
										enableDays[id] = jQuery.trim(val);
									});

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date)
									if (jQuery.inArray(sdate, enableDays) != -1) {
										return [true];
									}
									return [false];
								}
								// holiday single product start
								// holiday single product end 

								function enableAllTheseoverride(date) {

									var parsedTest = JSON.parse(newStr_over);

									var global_dates = parsedTest['disable_dates'];

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									
									console.log(window.dayOfWeek);
									window.dayOfWeek = jQuery.datepicker.formatDate('DD', date);
									console.log(window.dayOfWeek);	
									if (jQuery.inArray(sdate, global_dates) != -1) {
										return [true];
									}
									return [false];
								}

								function enableAllTheseoverride_noholidays(date) {

									var parsedTest = JSON.parse(newStr_over);

									var global_dates = parsedTest['disable_dates'];
									var holidays_dates = parsedTest['holiday_slots'];

									global_dates = global_dates.filter(function(el) {
										return holidays_dates.indexOf(el) < 0;
									});

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									
									

									if (jQuery.inArray(sdate, global_dates) != -1) {
										return [true];
									}
									return [false];
								}

								// holiday all product function start 
								function enableAllTheseoverride_holiday(date) {

									var parsedTest = JSON.parse(newStr_over);

									var global_dates = parsedTest['holiday_slots'];

									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(global_dates, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	 							

									var global_recurring = parsedTest['allow_recurring'];


									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date)
									if (jQuery.inArray(sdate, global_dates) == -1 && global_recurring == 'checked') {

										// recurring call start
										var string = jQuery.datepicker.formatDate('mm-dd', date);
										return [all_rec_holidays.indexOf(string) == -1]
										// recurring call end

									} else if (jQuery.inArray(sdate, global_dates) == -1) {
										return [true];
									}
									return [false];
								}

								function enableAllTheseoverride_deliverydaterange(date) {


									var parsedTest = JSON.parse(newStr_over);
									var global_dates = parsedTest['disable_dates_range'];


									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(global_dates, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	 							

									var global_recurring = parsedTest['allow_recurring'];


									var sdate           = jQuery.datepicker.formatDate('dd-mm-yy', date);
									
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									console.log(w)
									if (jQuery.inArray(sdate, global_dates) == -1) {

										return [false];
									} else {
										return [true];
									}
								}

								function enableAllTheseoverride_deliverydaterange_noholidays(date) {


									var parsedTest = JSON.parse(newStr_over);
									var global_dates = parsedTest['disable_dates_range'];

									var holidays = parsedTest['holiday_slots'];
									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(global_dates, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	 							

									var global_recurring = parsedTest['allow_recurring'];


									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
										
										
										
									global_dates = global_dates.filter(function(el) {
										return holidays.indexOf(el) < 0;
									});
									if (jQuery.inArray(sdate, global_dates) == -1) {

										return [false];
									} else {
										return [true];
									}
								}
								// holiday all product function end 

								// holiday single product function start 
								function enableAllTheseoverride_holiday_single(date) {

									var parsedTest = JSON.parse(strdates);

									var global_dates = parsedTest['holiday_slots'];
									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date)
									if (jQuery.inArray(sdate, parsedTest) == -1) {
										return [true];
									}
									return [false];
								}

								function enableAllTheseoverride_holiday_single_recurring(date) {

									var parsedTest = JSON.parse(strdates);

									var global_dates = parsedTest['holiday_slots'];

									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(parsedTest, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
										
										
									if (jQuery.inArray(sdate, parsedTest) == -1) {
										// return [true];
										// recurring call start
										var string = jQuery.datepicker.formatDate('mm-dd', date);
										return [all_rec_holidays.indexOf(string) == -1]
										// recurring call end
									}
									return [false];
								}
								// holiday single product function end 


								function enableAllTheseoverride2(y, date) {

									var parsedTest = JSON.parse(newStr_over);
									var global_days = parsedTest['avail_days'];

									y = y || new Date().getFullYear();
									var A = [];
									var B = [];
									var C = [];
									var E = [];
									var F = [];
									var H = [];
									var I = [];

									if (jQuery.inArray("0", global_days) == "-1") {

									} else {
										var D = new Date(y, 0, 1);
										var day = D.getDay();

										if (day != 0) {
											D.setDate(D.getDate() + (7 - day));

										}
										temp1 = D.toLocaleString();
										var temp1 = temp1.replace(/[^a-zA-Z 0-9]+/g, '-');
										A[0] = temp1;

										do {
											D.setDate(D.getDate() + 7);
											temp = D.toLocaleDateString();
											var temp = temp.replace(/[^a-zA-Z 0-9]+/g, '-');

											if (D.getFullYear() == y) {
												A.push(temp);

											}


										} while (D && D.getFullYear() == y)
									}

									if (jQuery.inArray("1", global_days) == "-1") {

									} else {
										var D1 = new Date(y, 0, 1);
										var day = D1.getDay();
										if (day != 0) {
											D1.setDate(D1.getDate() + (1 - day));

										}
										mondays = D1.toLocaleString();
										var mondays = mondays.replace(/[^a-zA-Z 0-9]+/g, '-');
										B[0] = mondays;

										do {

											D1.setDate(D1.getDate() + 7);
											mondays1 = D1.toLocaleDateString();
											var mondays1 = mondays1.replace(/[^a-zA-Z 0-9]+/g, '-');

											B.push(mondays1);


										} while (D1 && D1.getFullYear() == y)
									}

									if (jQuery.inArray("2", global_days) == "-1") {} else {
										var D2 = new Date(y, 0, 1);
										var day = D2.getDay();
										if (day != 0) {
											D2.setDate(D2.getDate() + (2 - day));

										}
										tuesdays = D2.toLocaleString();
										var tuesdays = tuesdays.replace(/[^a-zA-Z 0-9]+/g, '-');
										C[0] = tuesdays;

										do {

											D2.setDate(D2.getDate() + 7);
											tuesdays1 = D2.toLocaleDateString();
											var tuesdays1 = tuesdays1.replace(/[^a-zA-Z 0-9]+/g, '-');
											C.push(tuesdays1);


										} while (D2 && D2.getFullYear() == y)
									}

									if (jQuery.inArray("3", global_days) == "-1") {} else {
										var D3 = new Date(y, 0, 1);
										var day = D3.getDay();

										if (day != 0) {
											D3.setDate(D3.getDate() + (3 - day));

										}
										weds = D3.toLocaleString();
										var weds = weds.replace(/[^a-zA-Z 0-9]+/g, '-');
										E[0] = weds;

										do {

											D3.setDate(D3.getDate() + 7);
											weds1 = D3.toLocaleDateString();
											var weds1 = weds1.replace(/[^a-zA-Z 0-9]+/g, '-');
											E.push(weds1);


										} while (D3 && D3.getFullYear() == y)
									}

									if (jQuery.inArray("4", global_days) == "-1") {} else {
										var D4 = new Date(y, 0, 1);
										var day = D4.getDay();
										if (day != 0) {
											D4.setDate(D4.getDate() + (4 - day));

										}
										thus = D4.toLocaleString();
										var thus = thus.replace(/[^a-zA-Z 0-9]+/g, '-');
										F[0] = thus;

										do {

											D4.setDate(D4.getDate() + 7);
											thus1 = D4.toLocaleDateString();
											var thus1 = thus1.replace(/[^a-zA-Z 0-9]+/g, '-');
											F.push(thus1);


										} while (D4 && D4.getFullYear() == y)
									}

									if (jQuery.inArray("5", global_days) == "-1") {} else {
										var D5 = new Date(y, 0, 1);
										var day = D5.getDay();
										if (day != 0) {
											D5.setDate(D5.getDate() + (5 - day));

										}
										fri = D5.toLocaleString();
										var fri = fri.replace(/[^a-zA-Z 0-9]+/g, '-');
										H[0] = fri;

										do {

											D5.setDate(D5.getDate() + 7);
											fri1 = D5.toLocaleDateString();
											var fri1 = fri1.replace(/[^a-zA-Z 0-9]+/g, '-');

											H.push(fri1);


										} while (D5 && D5.getFullYear() == y)
									}
									if (jQuery.inArray("6", global_days) == "-1") {} else {
										var D6 = new Date(y, 0, 1);
										var day = D6.getDay();
										if (day != 0) {
											D6.setDate(D6.getDate() + (6 - day));

										}
										sat = D6.toLocaleString();
										var sat = sat.replace(/[^a-zA-Z 0-9]+/g, '-');
										I[0] = sat;

										do {

											D6.setDate(D6.getDate() + 7);
											sat1 = D6.toLocaleDateString();
											var sat1 = sat1.replace(/[^a-zA-Z 0-9]+/g, '-');

											I.push(sat1);


										} while (D6 && D6.getFullYear() == y)
									}
									A = A.concat(B).concat(C).concat(E).concat(F).concat(H).concat(I);
									var holidays_dates = parsedTest['holiday_slots'];
									A = A.filter(function(el) {
										return holidays_dates.indexOf(el) < 0;
									});

									return A;

								}

								function enableAllTheseoverride_no_holidays2(date) {

									var parsedTest = JSON.parse(newStr_over);
									var global_days = parsedTest['avail_days'];

									return global_days;
								}

								if (type_deliverys == to_chk_date)

								{


									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseDays,
										minDate: 0
									});

								} else if (split_override[0] == to_chk_override_dates) {

									if (check_holiday == "checked") {
										jQuery('.to-date').datepicker({


											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride_noholidays,
											minDate: 0
										});
									} else {
										jQuery('.to-date').datepicker({


											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride,
											minDate: 0
										});
									}

								}
								// holiday response start




								// holiday response end 

								// holiday single product response start
								else if (type_deliverys == to_check_single_pro_holiday) {

									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseoverride_holiday_single,
										minDate: 0
									});

								} else if (type_deliverys == 'Override_recurring_slot') {

									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseoverride_holiday_single_recurring,
										minDate: 0
									});

								}
								// holiday single product response end 
								else if (split_override[0] == to_chk_override_days) {

									if (check_holiday == "checked") {
										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: function(date) {
												var newString = output.slice(0, output.length - 1);
												var object = enableAllTheseoverride2();


												var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
													

												if (jQuery.inArray(sdate, object) != -1) {
													return [true];
												}
												return [false];
											},

											minDate: 0

										});

										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride2,
											minDate: 0
										});

									} else {
										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: function(d) {
												var newString = output.slice(0, output.length - 1);
												var object = enableAllTheseoverride_no_holidays2();
												var day = d.getDay();
												var a = object[0];
												var b = object[1];
												var c = object[2];
												var d = object[3];
												var e = object[4];
												var f = object[5];
												var g = object[6];
												return [(day == a || day == b || day == c || day == d || day == e || day == f || day == g)];

											},
											minDate: 0
										});
									}
								} else if (split_override[0] == to_chk_override_dates_range) {
									if (check_holiday == "checked") {

										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride_deliverydaterange_noholidays,
											minDate: 0
										});
									} else {
										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride_deliverydaterange,
											minDate: 0
										});
									}
								} else {

									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseDays,
										minDate: 0
									});

								}


							})
						}
					});
				});
			</script>
		<?php
			echo "</tr>
		</tbody></table>";
		} else {
			echo "<tr class='calendar_section'>";
			echo "<td class='Delivery_d'>" . esc_html__('Delivery Date', 'Delivery-Date') . "</td>";

			echo "<td class='input-date'><input type='text' required class='to-date' name='custom_date' value='' autocomplete='off'></td>";
			echo "<input name='pro' type='hidden' id='transferInput'>";
			global $product;
			$id = $product->get_id();
			echo "<input type='hidden' id='time-slot-user' value='" . $id . "'>";
			echo '<script>let productId = "' . $id . '" </script>';
		?>

			<script language='javascript'>
				jQuery("document").ready(function() {
					var product_id = (jQuery('.fp_prime_value').data('productid') !== undefined) ? jQuery('.fp_prime_value').data('productid') : productId;

					//var product_id = jQuery('.fp_prime_value').data('productid');

					var jsdata = product_id;
					jQuery('#transferInput').val(jsdata);
					jQuery.ajax({
						type: 'POST',

						url: my_ajax_object.ajax_url,
						data: {
							action: 'dld_as_read',
							dataType: 'json',
							// add your parameters here
							id: jQuery('#transferInput').val()
						},
						success: function(output) {


							var newStr = output.slice(0, output.length - 1);
							var newStr_over = output.slice(0, output.length - 1);

							strdates = newStr.substring(newStr.indexOf('[') + 0);
							before_split_override = newStr.substring(newStr.indexOf(':"') + 2);

							split_override = before_split_override.split('",');

							try{
								var parsedTest_holidays = JSON.parse(newStr_over);
								
							}catch(e){
								var parsedTest_holidays = []
							}
							var check_holiday = parsedTest_holidays['holiday_check'];

							var type_delivery = newStr.substr(0, newStr.indexOf('['));
							var type_deliverys = type_delivery.trim();

							jQuery(function() {

								var to_chk_date = 'Delivery_dates';
								var to_chk_override_dates = 'override_dates';
								var to_chk_override_days = 'override_days';
								var to_chk_override_holiday = 'override_holiday';
								var to_chk_override_dates_range = 'override_dates_range';
								var to_check_single_pro_holiday = 'Delivery_Holiday';

								function enableAllTheseDays(date) {

									try{

										var obj = JSON.parse(strdates);
									}catch(e){
										var obj = []
									}
									var enableDays = obj;
									jQuery.each(enableDays, function(id, val) {
										enableDays[id] = jQuery.trim(val);
									});

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date)
									
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									if(jQuery.inArray(sdate, enableDays) != -1) {
										return [true];
									}
									return [false];
								}
								// holiday single product start
								// holiday single product end 

								function enableAllTheseoverride(date) {

									var parsedTest = JSON.parse(newStr_over);

									var global_dates = parsedTest['disable_dates'];

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									console.log(w)

									if (jQuery.inArray(sdate, global_dates) != -1) {
										return [true];
									}
									return [false];
								}

								function enableAllTheseoverride_noholidays(date) {

									var parsedTest = JSON.parse(newStr_over);

									var global_dates = parsedTest['disable_dates'];
									var holidays_dates = parsedTest['holiday_slots'];

									global_dates = global_dates.filter(function(el) {
										return holidays_dates.indexOf(el) < 0;
									});

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									console.log(w)

									if (jQuery.inArray(sdate, global_dates) != -1) {
										return [true];
									}
									return [false];
								}

								// holiday all product function start 
								function enableAllTheseoverride_holiday(date) {

									var parsedTest = JSON.parse(newStr_over);

									var global_dates = parsedTest['holiday_slots'];

									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(global_dates, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	 							

									var global_recurring = parsedTest['allow_recurring'];


									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date)
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									console.log(w)
									if (jQuery.inArray(sdate, global_dates) == -1 && global_recurring == 'checked') {

										// recurring call start
										var string = jQuery.datepicker.formatDate('mm-dd', date);
										return [all_rec_holidays.indexOf(string) == -1]
										// recurring call end

									} else if (jQuery.inArray(sdate, global_dates) == -1) {
										return [true];
									}
									return [false];
								}

								function enableAllTheseoverride_deliverydaterange(date) {


									var parsedTest = JSON.parse(newStr_over);
									var global_dates = parsedTest['disable_dates_range'];


									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(global_dates, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	 							

									var global_recurring = parsedTest['allow_recurring'];


									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									
									console.log(window.dayOfWeek);
									

									if (jQuery.inArray(sdate, global_dates) == -1) {

										return [false];
									} else {
										return [true];
									}
								}

								function enableAllTheseoverride_deliverydaterange_noholidays(date) {


									var parsedTest = JSON.parse(newStr_over);
									var global_dates = parsedTest['disable_dates_range'];

									var holidays = parsedTest['holiday_slots'];
									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(global_dates, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	 							

									var global_recurring = parsedTest['allow_recurring'];


									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									
									console.log(window.dayOfWeek);
									

									global_dates = global_dates.filter(function(el) {
										return holidays.indexOf(el) < 0;
									});
									if (jQuery.inArray(sdate, global_dates) == -1) {

										return [false];
									} else {
										return [true];
									}
								}
								// holiday all product function end 

								// holiday single product function start 
								function enableAllTheseoverride_holiday_single(date) {

									var parsedTest = JSON.parse(strdates);

									var global_dates = parsedTest['holiday_slots'];
									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date)
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									
									console.log(window.dayOfWeek);
									
									if (jQuery.inArray(sdate, parsedTest) == -1) {
										return [true];
									}
									return [false];
								}

								function enableAllTheseoverride_holiday_single_recurring(date) {

									var parsedTest = JSON.parse(strdates);

									var global_dates = parsedTest['holiday_slots'];

									// recuring start							
									var all_rec_holidays = [];
									jQuery.each(parsedTest, function(key, value) {
										var arr = value.split('-');

										var holiday_dates = arr[1] + "-" + arr[0];

										all_rec_holidays.push(holiday_dates);
									});

									var holiday_for_aray = all_rec_holidays;
									// recuring end	

									var sdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
									window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
									
									console.log(window.dayOfWeek);
									

									if (jQuery.inArray(sdate, parsedTest) == -1) {
										// return [true];
										// recurring call start
										var string = jQuery.datepicker.formatDate('mm-dd', date);
										return [all_rec_holidays.indexOf(string) == -1]
										// recurring call end
									}
									return [false];
								}
								// holiday single product function end 


								function enableAllTheseoverride2(y, date) {

									var parsedTest = JSON.parse(newStr_over);
									var global_days = parsedTest['avail_days'];

									y = y || new Date().getFullYear();
									var A = [];
									var B = [];
									var C = [];
									var E = [];
									var F = [];
									var H = [];
									var I = [];

									if (jQuery.inArray("0", global_days) == "-1") {

									} else {
										var D = new Date(y, 0, 1);
										var day = D.getDay();

										if (day != 0) {
											D.setDate(D.getDate() + (7 - day));

										}
										temp1 = D.toLocaleString();
										var temp1 = temp1.replace(/[^a-zA-Z 0-9]+/g, '-');
										A[0] = temp1;

										do {
											D.setDate(D.getDate() + 7);
											temp = D.toLocaleDateString();
											var temp = temp.replace(/[^a-zA-Z 0-9]+/g, '-');

											if (D.getFullYear() == y) {
												A.push(temp);

											}


										} while (D && D.getFullYear() == y)
									}

									if (jQuery.inArray("1", global_days) == "-1") {

									} else {
										var D1 = new Date(y, 0, 1);
										var day = D1.getDay();
										if (day != 0) {
											D1.setDate(D1.getDate() + (1 - day));

										}
										mondays = D1.toLocaleString();
										var mondays = mondays.replace(/[^a-zA-Z 0-9]+/g, '-');
										B[0] = mondays;

										do {

											D1.setDate(D1.getDate() + 7);
											mondays1 = D1.toLocaleDateString();
											var mondays1 = mondays1.replace(/[^a-zA-Z 0-9]+/g, '-');

											B.push(mondays1);


										} while (D1 && D1.getFullYear() == y)
									}

									if (jQuery.inArray("2", global_days) == "-1") {} else {
										var D2 = new Date(y, 0, 1);
										var day = D2.getDay();
										if (day != 0) {
											D2.setDate(D2.getDate() + (2 - day));

										}
										tuesdays = D2.toLocaleString();
										var tuesdays = tuesdays.replace(/[^a-zA-Z 0-9]+/g, '-');
										C[0] = tuesdays;

										do {

											D2.setDate(D2.getDate() + 7);
											tuesdays1 = D2.toLocaleDateString();
											var tuesdays1 = tuesdays1.replace(/[^a-zA-Z 0-9]+/g, '-');
											C.push(tuesdays1);


										} while (D2 && D2.getFullYear() == y)
									}

									if (jQuery.inArray("3", global_days) == "-1") {} else {
										var D3 = new Date(y, 0, 1);
										var day = D3.getDay();

										if (day != 0) {
											D3.setDate(D3.getDate() + (3 - day));

										}
										weds = D3.toLocaleString();
										var weds = weds.replace(/[^a-zA-Z 0-9]+/g, '-');
										E[0] = weds;

										do {

											D3.setDate(D3.getDate() + 7);
											weds1 = D3.toLocaleDateString();
											var weds1 = weds1.replace(/[^a-zA-Z 0-9]+/g, '-');
											E.push(weds1);


										} while (D3 && D3.getFullYear() == y)
									}

									if (jQuery.inArray("4", global_days) == "-1") {} else {
										var D4 = new Date(y, 0, 1);
										var day = D4.getDay();
										if (day != 0) {
											D4.setDate(D4.getDate() + (4 - day));

										}
										thus = D4.toLocaleString();
										var thus = thus.replace(/[^a-zA-Z 0-9]+/g, '-');
										F[0] = thus;

										do {

											D4.setDate(D4.getDate() + 7);
											thus1 = D4.toLocaleDateString();
											var thus1 = thus1.replace(/[^a-zA-Z 0-9]+/g, '-');
											F.push(thus1);


										} while (D4 && D4.getFullYear() == y)
									}

									if (jQuery.inArray("5", global_days) == "-1") {} else {
										var D5 = new Date(y, 0, 1);
										var day = D5.getDay();
										if (day != 0) {
											D5.setDate(D5.getDate() + (5 - day));

										}
										fri = D5.toLocaleString();
										var fri = fri.replace(/[^a-zA-Z 0-9]+/g, '-');
										H[0] = fri;

										do {

											D5.setDate(D5.getDate() + 7);
											fri1 = D5.toLocaleDateString();
											var fri1 = fri1.replace(/[^a-zA-Z 0-9]+/g, '-');

											H.push(fri1);


										} while (D5 && D5.getFullYear() == y)
									}
									if (jQuery.inArray("6", global_days) == "-1") {} else {
										var D6 = new Date(y, 0, 1);
										var day = D6.getDay();
										if (day != 0) {
											D6.setDate(D6.getDate() + (6 - day));

										}
										sat = D6.toLocaleString();
										var sat = sat.replace(/[^a-zA-Z 0-9]+/g, '-');
										I[0] = sat;

										do {

											D6.setDate(D6.getDate() + 7);
											sat1 = D6.toLocaleDateString();
											var sat1 = sat1.replace(/[^a-zA-Z 0-9]+/g, '-');

											I.push(sat1);


										} while (D6 && D6.getFullYear() == y)
									}
									A = A.concat(B).concat(C).concat(E).concat(F).concat(H).concat(I);
									var holidays_dates = parsedTest['holiday_slots'];
									A = A.filter(function(el) {
										return holidays_dates.indexOf(el) < 0;
									});

									return A;

								}

								function enableAllTheseoverride_no_holidays2(date) {

									var parsedTest = JSON.parse(newStr_over);
									var global_days = parsedTest['avail_days'];

									return global_days;
								}

								if (type_deliverys == to_chk_date)

								{


									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseDays,
										minDate: 0
									});

								} else if (split_override[0] == to_chk_override_dates) {

									if (check_holiday == "checked") {
										jQuery('.to-date').datepicker({


											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride_noholidays,
											minDate: 0
										});
									} else {
										jQuery('.to-date').datepicker({


											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride,
											minDate: 0
										});
									}

								}
								// holiday response start




								// holiday response end 

								// holiday single product response start
								else if (type_deliverys == to_check_single_pro_holiday) {

									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseoverride_holiday_single,
										minDate: 0
									});

								} else if (type_deliverys == 'Override_recurring_slot') {

									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseoverride_holiday_single_recurring,
										minDate: 0
									});

								}
								// holiday single product response end 
								else if (split_override[0] == to_chk_override_days) {

									if (check_holiday == "checked") {
										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: function(date) {
												var newString = output.slice(0, output.length - 1);
												var object = enableAllTheseoverride2();


												window.dayOfWeek    = jQuery.datepicker.formatDate('DD', date);
												
												console.log(window.dayOfWeek);
												

												if (jQuery.inArray(sdate, object) != -1) {
													return [true];
												}
												return [false];
											},

											minDate: 0

										});

										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride2,
											minDate: 0
										});

									} else {
										let chosenDate = jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: function(d) {
												var newString = output.slice(0, output.length - 1);
												var object = enableAllTheseoverride_no_holidays2();
												var day = d.getDay();
												window.selectedDayOfWeek = day;
												var a = object[0];
												var b = object[1];
												var c = object[2];
												var d = object[3];
												var e = object[4];
												var f = object[5];
												var g = object[6];
												return [(day == a || day == b || day == c || day == d || day == e || day == f || day == g)];

											},
											minDate: 0
										});
										try{
											console.log(window.selectedDayOfWeek);	
										}catch(e){
											console.error(e);
										}
									}
								} else if (split_override[0] == to_chk_override_dates_range) {
									if (check_holiday == "checked") {

										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride_deliverydaterange_noholidays,
											minDate: 0
										});
									} else {
										jQuery('.to-date').datepicker({
											dateFormat: 'dd-mm-yy',
											beforeShowDay: enableAllTheseoverride_deliverydaterange,
											minDate: 0
										});
									}
								} else {

									jQuery('.to-date').datepicker({
										dateFormat: 'dd-mm-yy',
										beforeShowDay: enableAllTheseDays,
										minDate: 0
									});

								}


							})
						}
					});
				});
			</script>
<?php
			echo "</tr>
			</tbody></table>";
		}
	}



	///add_action('wp_ajax_get_date', 'get_date');


}
