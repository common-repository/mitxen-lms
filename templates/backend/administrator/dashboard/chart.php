<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;


$months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
$month_wise_income = array();

for ($i = 0; $i < 12; $i++) {
    $first_day_of_month = strtotime("1 " . ucfirst($months[$i]) . " " . date("Y") . ' 00:00:00');
    $last_day_of_month = strtotime(date("t", strtotime($first_day_of_month)) . " " . ucfirst($months[$i]) . " " . date("Y") . ' 00:00:00');

    global $wpdb;
    $table = self::$tables['payment'];
    $result = $wpdb->get_var($wpdb->prepare("SELECT sum(admin_revenue) FROM $table WHERE `date_added` >= %d AND `date_added` <= %d", $first_day_of_month, $last_day_of_month));

    $total_admin_revenue = $result;
    $total_admin_revenue > 0 ? array_push($month_wise_income, $total_admin_revenue) : array_push($month_wise_income, 0);
}
?>

<!-- Chart code -->
<script>
    "use strict";
    // SALES GRAPH
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        var chart = am4core.create("chartdiv", am4charts.XYChart);

        var data = [];

        chart.data = [{
            "month": "Jan",
            "income": '<?php echo esc_js($month_wise_income[0]); ?>'
        }, {
            "month": "Feb",
            "income": '<?php echo esc_js($month_wise_income[1]); ?>'
        }, {
            "month": "Mar",
            "income": '<?php echo esc_js($month_wise_income[2]); ?>'
        }, {
            "month": "Apr",
            "income": '<?php echo esc_js($month_wise_income[3]); ?>'
        }, {
            "month": "May",
            "income": '<?php echo esc_js($month_wise_income[4]); ?>'
        }, {
            "month": "Jun",
            "income": '<?php echo esc_js($month_wise_income[5]); ?>'
        }, {
            "month": "Jul",
            "income": '<?php echo esc_js($month_wise_income[6]); ?>'
        }, {
            "month": "Aug",
            "income": '<?php echo esc_js($month_wise_income[7]); ?>'
        }, {
            "month": "Sep",
            "income": '<?php echo esc_js($month_wise_income[8]); ?>'
        }, {
            "month": "Oct",
            "income": '<?php echo esc_js($month_wise_income[9]); ?>'
        }, {
            "month": "Nov",
            "income": '<?php echo esc_js($month_wise_income[10]); ?>'
        }, {
            "month": "Dec",
            "income": '<?php echo esc_js($month_wise_income[11]); ?>'
        }];

        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.ticks.template.disabled = true;
        categoryAxis.renderer.line.opacity = 0;
        categoryAxis.renderer.grid.template.disabled = true;
        categoryAxis.renderer.minGridDistance = 40;
        categoryAxis.dataFields.category = "month";
        categoryAxis.startLocation = 0.4;
        categoryAxis.endLocation = 0.6;


        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.tooltip.disabled = true;
        valueAxis.renderer.line.opacity = 0;
        valueAxis.renderer.ticks.template.disabled = true;
        valueAxis.min = 0;

        var lineSeries = chart.series.push(new am4charts.LineSeries());
        lineSeries.dataFields.categoryX = "month";
        lineSeries.dataFields.valueY = "income";
        lineSeries.tooltipText = "income: {valueY.value}";
        lineSeries.fillOpacity = 0.5;
        lineSeries.strokeWidth = 3;
        lineSeries.propertyFields.stroke = "lineColor";
        lineSeries.propertyFields.fill = "lineColor";

        var bullet = lineSeries.bullets.push(new am4charts.CircleBullet());
        bullet.circle.radius = 6;
        bullet.circle.fill = am4core.color("#fff");
        bullet.circle.strokeWidth = 3;

        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "panX";
        chart.cursor.lineX.opacity = 0;
        chart.cursor.lineY.opacity = 0;

        // DISABLE THE AMCHART LOGO
        chart.logo.disabled = true;
    });
</script>