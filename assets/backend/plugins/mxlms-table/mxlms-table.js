"use strict";

jQuery(document).on('mouseover', '.mxlms-responsive-table tbody tr',function(e) {
    jQuery(this).prev('tr').css('border-bottom', '1px solid #fff');
    jQuery(this).css('border-bottom', '1px solid #fff');
  });
  jQuery(document).on('mouseleave', '.mxlms-responsive-table tbody tr',function(e) {
    jQuery(this).prev('tr').css('border-bottom', '1px solid #e8f0fe');
    jQuery(this).css('border-bottom', '1px solid #e8f0fe');
  });
  
  jQuery(document).on('click', '.mxlms-clickable-row',function(e) {
    let elem = e.target;
    if (!jQuery(e.target).hasClass('mxlms-stop-prop') && jQuery(elem).parents('.mxlms-stop-prop').length === 0) {
        let elem2 = jQuery(this).closest('.mxlms-clickable-row');
        let callback = Function(jQuery(elem2).attr('callback'));
        callback();
    }
  });  