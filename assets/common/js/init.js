"use strict";
function initNiceSelect(ids) {
  if(ids && ids.length){
    let i;
    for(i = 0; i < ids.length; i++){
      jQuery(ids[i]).niceSelect();
    }
  }else{
    jQuery('select').niceSelect();
  }
}

// INIT DATE RANGE PICKER
function initDateRangePicker(ids) {
  let i;
  for(i = 0; i < ids.length; i++){
    var start = moment().startOf('month');
    var end = moment().endOf('month');
  
    function cb(start, end) {
        jQuery(ids[i]+' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
  
    jQuery(ids[i]).mxlms_daterangepicker({
        locale: {
            format: 'MMMM DD, YYYY'
        },
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
  
    cb(start, end);
  }
}