jQuery(document).ready(function() {
    jQuery(".daterange_wrapper").on("click", "#daterange_clone_monday", function(e) {
        let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_monday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_monday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
       // jQuery("#dd_monday_timeslots_1").hide()   
      });
    jQuery("#dd_monday_timeslots_1").hide()  
    jQuery(".daterange_wrapper").on("click", "#daterange_clone_tuesday", function() {
      let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_tuesday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_tuesday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
        jQuery("#dd_tuesday_timeslots_1").hide()           
    });
    jQuery(".daterange_wrapper").on("click", "#daterange_clone_wednesday", function() {
      let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_wednesday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_wednesday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
        jQuery("#dd_wednesday_timeslots_1").hide()   
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_clone_thursday", function() {
        let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_thursday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_thursday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
        jQuery("#dd_thursday_timeslots_1").hide()   
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_clone_friday", function() {
        let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_friday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_friday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
        jQuery("#dd_friday_timeslots_1").hide()     
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_clone_saturday", function() {
        let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_saturday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_saturday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
        jQuery("#dd_saturday_timeslots_1").hide()       
      });  
      jQuery(".daterange_wrapper").on("click", "#daterange_clone_sunday", function() {
        let id = Math.random().toString(36).substring(7);
        jQuery("#daterange_clone_sunday")
          .closest(".daterange_wrapper")
          .find(".daterange_timeslots")
          .first()
          .clone()
          .prop('id',id)
          .show()
          .appendTo(".daterange_results_sunday");
        jQuery('#'+id)
          .daterangepicker({
            timePicker: true,
    
            locale: {
              format: "hh:mm A"
            }
          })
          .on("show.daterangepicker", function(ev, picker) {
            picker.container.find(".calendar-table").hide();
          });
        jQuery("#dd_sunday_timeslots_1").hide()   
      });   
      
      jQuery(".daterange_wrapper").on("click", "#daterange_remove_monday", function() {
        jQuery("#daterange_remove_monday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });

      jQuery(".daterange_wrapper").on("click", "#daterange_remove_tuesday", function() {
        jQuery("#daterange_remove_tuesday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_remove_wednesday", function() {
        jQuery("#daterange_remove_wednesday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_remove_thursday", function() {
        jQuery("#daterange_remove_thursday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_remove_friday", function() {
        jQuery("#daterange_remove_friday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_remove_saturday", function() {
        jQuery("#daterange_remove_saturday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });
      jQuery(".daterange_wrapper").on("click", "#daterange_remove_sunday", function() {
        jQuery("#daterange_remove_sunday")
          .closest(".daterange_wrapper")
          .find(".daterange_datetimes")
          .not(":first")
          .last()
          .remove();
      });
});