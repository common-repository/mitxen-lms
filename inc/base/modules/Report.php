<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

ob_start();

use Mxlms\base\BaseController;
use Mxlms\base\modules\User;


defined('ABSPATH') or die('You can not access the file directly');

class Report extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_report', array($this, 'post'));
    }

    // Main method for handling all the post data submitted during a form submission
    public function post()
    {
        $task = sanitize_text_field($_POST['task']);
        $this->handle_posts($task);
    }

    // Method for handling form submission according to task of the form
    public function handle_posts($task)
    {
        switch ($task) {
            case 'export_admin_report':
                $this->export_admin_report();
                break;
            case 'export_instructor_report':
                $this->export_instructor_report();
                break;
        }
    }

    public static function get_admin_revenue_reports($start_date = "", $end_date = "", $page_number = "", $limit = "")
    {
        global $wpdb;
        $table = self::$tables['payment'];
        $start_date = (int)strtotime($start_date);
        $end_date   = (int)strtotime($end_date);

        if (!empty($page_number) && !empty($limit)) {
            $offset = (int)($page_number - 1)  * $limit;
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC LIMIT %d OFFSET %d", $start_date, $end_date, $limit, $offset ) );
            return $result;
        } else {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC", $start_date, $end_date ) );
            return $result;
        }
    }

    public static function get_instructor_revenue_reports($instructor_id = "", $start_date = "", $end_date = "", $page_number = "", $limit = "")
    {
        $instructor_courses = $instructor_id ? Course::get_instructor_courses_by_instructor_id($instructor_id) : Course::get_all_instructor_courses();
        $instructor_course_ids = array();

        foreach ($instructor_courses as $instructor_course) {
            if (!in_array($instructor_course->id, $instructor_course_ids)) {
                array_push($instructor_course_ids, esc_sql($instructor_course->id));
            }
        }

        if (count($instructor_course_ids)) {
            $instructor_course_ids = implode(", ", esc_sql($instructor_course_ids));
            global $wpdb;
            $table = self::$tables['payment'];

            $start_date = (int)strtotime($start_date);
            $end_date   = (int)strtotime($end_date);

            if (!empty($page_number) && !empty($limit)) {
                $offset = (int)($page_number - 1)  * $limit;
                $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `course_id` IN ($instructor_course_ids) AND `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC LIMIT %d OFFSET %d", $start_date, $end_date, $limit, $offset ) );
            } else {
                $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `course_id` IN ($instructor_course_ids) AND `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC", $start_date, $end_date ) );
            }

            return $result;
        }

        return array();
    }

    public static function get_total_instructor_revenue($instructor_id = "")
    {
        $instructor_courses = Course::get_instructor_courses_by_instructor_id($instructor_id);
        $instructor_course_ids = array();

        $total_revenue_amount = 0;

        foreach ($instructor_courses as $instructor_course) {
            if (!in_array($instructor_course->id, $instructor_course_ids)) {
                array_push($instructor_course_ids, esc_sql($instructor_course->id));
            }
        }

        if (count($instructor_course_ids)) {
            $instructor_course_ids = implode(", ", esc_sql($instructor_course_ids));
            global $wpdb;
            $table = self::$tables['payment'];

            $result = $wpdb->get_results( "SELECT * FROM $table WHERE `course_id` IN ($instructor_course_ids) ORDER BY $table.`date_added` ASC" );

            foreach ($result as $row) {
                $total_revenue_amount = $total_revenue_amount + $row->instructor_revenue;
            }

            return $total_revenue_amount;
        }

        return $total_revenue_amount;
    }


    public static function export_admin_report()
    {
        if (self::verify_nonce('export_admin_report_nonce') == true) {
            global $wpdb;
            $table = self::$tables['payment'];

            $start_date = isset($_POST['report_export_start_date']) ? sanitize_text_field($_POST['report_export_start_date']) : date("F d, Y");
            $end_date = isset($_POST['report_export_end_date']) ? sanitize_text_field($_POST['report_export_end_date']) : date("F t, Y");

            $start_date = (int)strtotime($start_date);
            $end_date   = (int)strtotime($end_date);

            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC", $start_date, $end_date ) );



            // open raw memory as file so no temp files needed, you might run out of memory though
            $f = fopen('php://memory', 'w');

            $exported_line_header = array('Course Title', 'Course Amount', 'Admin Revenue', 'Date');
            fputcsv($f, $exported_line_header, ',');
            // loop over the input array
            foreach ($result as $report) {
                // generate csv lines from the inner arrays
                $course_details = Course::get_authenticated_course_details_by_id($report->course_id);
                $exported_lines['Course Title'] = esc_html($course_details->title);
                $exported_lines['Course Amount'] = Helper::currency(esc_html($report->amount));
                $exported_lines['Admin Revenue'] = Helper::currency(esc_html($report->admin_revenue));
                $exported_lines['Date'] = date("D, d-M-Y", esc_html($report->date_added));
                fputcsv($f, $exported_lines, ',');
            }
            // reset the file pointer to the start of the file
            fseek($f, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            $filename = "export.csv";
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            // make php send the generated csv lines to the browser
            fpassthru($f);
        }
    }

    public static function export_instructor_report()
    {
        if (self::verify_nonce('export_instructor_report_nonce') == true) {

            if (Helper::get_current_user_role() == "instructor") {
                $instructor_id = Helper::get_current_user_id();
            } else {
                $instructor_id = false;
            }

            $instructor_courses = $instructor_id ? Course::get_instructor_courses_by_instructor_id($instructor_id) : Course::get_all_instructor_courses();
            $instructor_course_ids = array();

            foreach ($instructor_courses as $instructor_course) {
                if (!in_array($instructor_course->id, $instructor_course_ids)) {
                    array_push($instructor_course_ids, esc_sql($instructor_course->id));
                }
            }

            $start_date = isset($_POST['report_export_start_date']) ? sanitize_text_field($_POST['report_export_start_date']) : date("F d, Y");
            $end_date = isset($_POST['report_export_end_date']) ? sanitize_text_field($_POST['report_export_end_date']) : date("F t, Y");

            $start_date = (int)strtotime($start_date);
            $end_date   = (int)strtotime($end_date);

            if (count($instructor_course_ids)) {
                $instructor_course_ids = implode(", ", esc_sql($instructor_course_ids));
                global $wpdb;
                $table = self::$tables['payment'];
                $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `course_id` IN ($instructor_course_ids) AND `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC", $start_date, $end_date ) );
            } else {
                $result = array();
            }

            // open raw memory as file so no temp files needed, you might run out of memory though
            $f = fopen('php://memory', 'w');

            $exported_line_header = array('Instructor', 'Course Title', 'Course Amount', 'Instructor Revenue', 'Date');
            fputcsv($f, $exported_line_header, ',');
            // loop over the input array
            foreach ($result as $report) {
                // generate csv lines from the inner arrays
                $course_details = Course::get_authenticated_course_details_by_id($report->course_id);
                $instructor_details = Instructor::get_instructor_by_id($course_details->user_id);
                $exported_lines['Instructor'] = esc_html($instructor_details->first_name . ' ' . $instructor_details->last_name);
                $exported_lines['Course Title'] = esc_html($course_details->title);
                $exported_lines['Course Amount'] = Helper::currency(esc_html($report->amount));
                $exported_lines['Instructor Revenue'] = Helper::currency(esc_html($report->instructor_revenue));
                $exported_lines['Date'] = date("D, d-M-Y", esc_html($report->date_added));
                fputcsv($f, $exported_lines, ',');
            }
            // reset the file pointer to the start of the file
            fseek($f, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            $filename = "export.csv";
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            // make php send the generated csv lines to the browser
            fpassthru($f);
        }
    }


    // GET REPORT DETAILS BY ID
    public static function get_report_by_id($report_id)
    {
        global $wpdb;
        $table = self::$tables['payment'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `id` = %d", $report_id ) );
        return $result[0];
    }
}