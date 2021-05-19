/**
 * JavaScript template for Delivery Date
 *
 * JavaScript which includes customize scripts for Delivery Date
 *
 */

jQuery(document).on('change','.to-date',function(){
    "use strict";
    
    var sel_name=jQuery(this).attr('name');
    var id=jQuery(this).attr('id');
    var id2 =document.getElementById(id);
    var sel_date = jQuery(id2).val();
    var namearr = sel_name.split("]");
    var sel_data=jQuery(this).val(); 
 
        jQuery.ajax({
            url:my_ajax_object.ajax_url,
            type: 'POST',
            data: {
                'sel_date': sel_date,
                action: 'get_date'
            },
        
         success: function(data) {
            
       },
   });

});

jQuery(document).on('change','.to-date',function(){

    "use strict"; 
    jQuery.ajax({
        type: "POST",
        url:my_ajax_object.ajax_url,
        data: {
            action: 'dld_mark_message_as_read',
            // add your parameters here
            message_id: jQuery('.to-date').val()
        },
        success: function (output) {
           jQuery(".single_add_to_cart_button").show();
        }
        });
  
});

jQuery(document).on('change','.to-date',function()
{
    var user_current_date=jQuery(this).val();
    try{
        console.log(user_current_date);
        let current_date = jQuery(this).datepicker('getDate');
        var selected_day_of_week = jQuery.datepicker.formatDate("DD", current_date).toLowerCase();
        console.debug(selected_day_of_week);
    }catch(e){
        console.error(e);
    }
    var product_id = jQuery('#time-slot-user').val();
    
    jQuery.ajax({
        type: "POST",
        url:my_ajax_object.ajax_url,
        data: {
            date:user_current_date,product_id:product_id, day_of_week:selected_day_of_week,
            action: 'date_current_slot',
        },
        success: function (output) {
           var select =JSON.parse(output);
           jQuery('.select_time_slot')
                      .empty();
                  jQuery.each(select, function(key, value) {   
                      jQuery('.select_time_slot')
                          .append(jQuery("<option></option>")
                                     .attr("value",key)
                                     .text(value)); 
                 })
        }
        });

});

jQuery(document).on('keyup','.input_post_code',function()
{
    var postal_code = jQuery('.class_post_code').val();
    var product_id = jQuery('#postal-product-id').val();
    
    jQuery.ajax({
        type: "POST",
        url:my_ajax_object.ajax_url,
        data: {
            postal_code:postal_code,
            action: 'postal_code',
        },
        success: function (success) {

            if(success == 'true')
            {
                jQuery("#msg").html("");
                jQuery(".to-date").attr('disabled',false);
            }else{
                jQuery("#msg").html("We are not delivering in this area.");
                jQuery(".to-date").attr('disabled',true);
            }
        }
        });

});


