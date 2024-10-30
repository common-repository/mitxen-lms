<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules\payment;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Enrolment;
use Mxlms\base\modules\User;

defined('ABSPATH') or die('You can not access the file directly');

class Paypal extends BaseController
{
    public static function paypal_payment($paymentID = "")
    {
        $paypal_payment_info = json_decode(Helper::get_payment_settings('paypal'));

        if ($paypal_payment_info->mode == "sandbox") {
            $paypalClientID = $paypal_payment_info->sandbox_client_id;
            $paypalSecret = $paypal_payment_info->sandbox_secret_key;
        } else {
            $paypalClientID = $paypal_payment_info->production_client_id;
            $paypalSecret = $paypal_payment_info->production_secret_key;
        }

        $paypalEnv = $paypal_payment_info->mode; // Or 'production'

        if ($paypalEnv == 'sandbox') {
            $paypalURL = 'https://api.sandbox.paypal.com/v1/';
        } else {
            $paypalURL = 'https://api.paypal.com/v1/';
        }

        $auth = base64_encode($paypalClientID . ":" . $paypalSecret);
        $response = wp_remote_post(
            $paypalURL . 'oauth2/token',
            array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(
                    'Authorization' => "Basic $auth"
                ),
                'body'        => array(
                    'grant_type' => 'client_credentials'
                ),
                'cookies'     => array()
            )
        );

        if (wp_remote_retrieve_response_code($response) == 200) {
            $body  = json_decode(wp_remote_retrieve_body($response), true);
            $access_token = $body['access_token'];
            $payment_response = wp_remote_get(
                $paypalURL . 'payments/payment/' . $paymentID,
                array(
                    'method'  => 'GET',
                    'timeout' => 45,
                    'httpversion' => '1.0',
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $access_token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/xml'
                    )
                )
            );

            if (wp_remote_retrieve_response_code($payment_response) == 200) {
                $payment_response_decoded = json_decode(wp_remote_retrieve_body($payment_response), true);
                // CHECK IF THE PAYMENT STATE IS APPROVED OR NOT
                if (isset($payment_response_decoded['state']) && $payment_response_decoded['state'] == 'approved') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function record_payment_data($course_id, $paymentID)
    {
        $table = self::$tables['payment'];
        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `paypal_pay_id` = %s", $paymentID));
        if ($result && count($result) > 0) {
            return false;
        } else {
            $course_details = Course::get_course_details_by_id($course_id);
            $instructor_details = User::get_user_by_id($course_details->user_id);

            $data['user_id'] = Helper::get_current_user_id();
            $data['payment_type'] = 'paypal';
            $data['course_id'] = $course_id;
            $data['amount'] = Helper::get_course_price($course_id);
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $data['paypal_pay_id'] = $paymentID;

            if ($instructor_details->role == "admin") {
                $data['admin_revenue'] = $data['amount'];
                $data['instructor_revenue'] = 0;
            } else {
                $instructor_revenue_percentage = Helper::get_instructor_settings('instructor_revenue_percentage');
                $data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
                $data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
            }

            $wpdb->insert($table, $data);

            return Enrolment::enrol_after_payment($data['user_id'], $data['course_id']);
        }
    }
}
