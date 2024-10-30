<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;

use Mxlms\base\modules\Helper;

$is_succeeded = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_URL);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_URL);


// HANDLES STUDENT REGISTRATION
if ($_POST) {
    if (BaseController::verify_nonce('add_student_nonce')) {


        if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['username'])) {
            wp_redirect(esc_url_raw(Helper::get_url("page-contains=signup&success=false&error=empty-field")));
            exit;
        }

        // Create a wp user of role 'student'
        $username   = sanitize_text_field($_POST['username']);
        $email      = sanitize_email($_POST['email']);
        $password   = sanitize_text_field($_POST['password']);

        // Return if username or email already exists otherwise add user
        if (username_exists($username) || email_exists($email)) {
            wp_redirect(esc_url_raw(Helper::get_url("page-contains=signup&success=false&error=duplication")));
            exit;
        } else {
            $user_id = wp_create_user($username, $password, $email);
            $user = get_user_by('id', $user_id);
            $user->remove_role('subscriber');
            $user->add_role(BaseController::$custom_roles['student']['role']);

            // GET FULL NAME
            $full_name = sanitize_text_field($_POST['fullname']);
            $exploded_full_name = explode(' ', $full_name);
            $name_size = count($exploded_full_name);
            if ($name_size > 1) {
                $data['last_name']     =   end($exploded_full_name);
                for ($i = 0; $i < ($name_size - 1); $i++) {
                    $data['first_name'] = $data['first_name'] . ' ' . $exploded_full_name[$i];
                }

                $data['first_name'] = trim($data['first_name']);
            } else {
                $data['first_name'] = $full_name;
                $data['last_name'] = "";
            }


            // Create an entry within plugin's user table
            $data['wp_user_id']     =   $user_id;
            $data['email']          =   sanitize_text_field($_POST['email']);
            $data['role']           =   'student';

            // SOCIAL INFORMATION
            $social_link['facebook'] = sanitize_text_field($_POST['facebook_link']);
            $social_link['twitter'] = sanitize_text_field($_POST['twitter_link']);
            $social_link['linkedin'] = sanitize_text_field($_POST['linkedin_link']);
            $data['social_links'] = json_encode($social_link);

            // Add paypal keys
            $paypal_info = array(
                'production_client_id' => sanitize_text_field($_POST['paypal_client_id']),
                'production_secret_key' => sanitize_text_field($_POST['paypal_secret_key'])
            );
            $data['paypal_keys'] = json_encode($paypal_info);

            // Add Stripe keys
            $stripe_info = array(
                'public_live_key' => sanitize_text_field($_POST['stripe_public_key']),
                'secret_live_key' => sanitize_text_field($_POST['stripe_secret_key'])
            );
            $data['stripe_keys'] = json_encode($stripe_info);

            $data['status'] = 1;

            global $wpdb;
            $wpdb->insert(BaseController::$tables['users'], $data);
            wp_redirect(esc_url_raw(Helper::get_url("page-contains=signup&success=true")));
            exit;
        }
    } else {
        wp_redirect(esc_url_raw(Helper::get_url("page-contains=signup&success=false&error=invalid-nonce")));
        exit;
    }
}
?>
<div class="mxlms-container-fluid">
    <?php include 'mxlms-page-navbar.php'; ?>
    <div class="mxlms-row mxlms-justify-content-center">
        <!-- FORM ELEMENT -->
        <div class="mxlms-col-md-6 mxlms-account-info">
            <i class="las la-user-plus"></i>
            <div class="mxlms-title-login">
                <?php esc_html_e('Signup to', BaseController::$text_domain); ?> <?php echo Helper::get_general_settings('system_name'); ?>
            </div>
        </div>
        <div class="mxlms-col-md-6">
            <?php if (!empty($is_succeeded)) : ?>
                <?php if ($is_succeeded == "true") : ?>
                    <div class="mxlms-alert mxlms-alert-success mxlms-font-16 mxlms-text-dark"><?php esc_html_e('Registration has been done successfully', BaseController::$text_domain); ?></div>
                <?php else : ?>
                    <?php if ($error == "duplication") : ?>
                        <div class="mxlms-alert mxlms-alert-danger mxlms-font-16 mxlms-text-dark"><?php esc_html_e('Duplication Email or Username', BaseController::$text_domain); ?></div>
                    <?php elseif ($error == "empty-field") : ?>
                        <div class="mxlms-alert mxlms-alert-danger mxlms-font-16 mxlms-text-dark"><?php esc_html_e('Fields can not be empty', BaseController::$text_domain); ?></div>
                    <?php else : ?>
                        <div class="mxlms-alert mxlms-alert-danger mxlms-font-16 mxlms-text-dark"><?php esc_html_e('Invalid Form Submission', BaseController::$text_domain); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <div class="mxlms-card mxlms-account">
                <div class="mxlms-card-header mxlms-pt-3">
                    <div class="mxlms-card-title">
                        <?php esc_html_e('Signup', BaseController::$text_domain); ?>
                    </div>
                </div>
                <div class="mxlms-card-body">
                    <form action="" method="post" class='mxlms-form mxlms-form-layout' enctype='multipart/form-data' autocomplete="off">
                        <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_student'; ?>">
                        <input type="hidden" name="task" value="add_student">
                        <input type="hidden" name="add_student_nonce" value="<?php echo wp_create_nonce('add_student_nonce'); ?>"> <!-- kind of csrf token-->
                        <div class="mxlms-field">
                            <label class="mxlms-text-label  mxlms-fieldlabel-layout" for="name"><?php esc_html_e('Name', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
                            <input class="mxlms-input" type="text" id="name" name="fullname" placeholder="" autocomplete="off" />
                        </div>
                        <div class="mxlms-field">
                            <label class="mxlms-text-label  mxlms-fieldlabel-layout" for="username"><?php esc_html_e('Username', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
                            <input class="mxlms-input" type="text" id="username" name="username" placeholder="" autocomplete="off" />
                        </div>
                        <div class="mxlms-field">
                            <label class="mxlms-text-label  mxlms-fieldlabel-layout" for="email"><?php esc_html_e('Email', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
                            <input class="mxlms-input" type="email" id="email" name="email" placeholder="" autocomplete="off" />
                        </div>
                        <div class="mxlms-field">
                            <label class="mxlms-text-label  mxlms-fieldlabel-layout" for="password"><?php esc_html_e('Password', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
                            <input class="mxlms-input" type="text" id="password" name="password" autocomplete="off" />
                        </div>
                        <div class="mxlms-field">
                            <button class="mxlms-button mxlms-btn-secondary mxlms-block mxlms-round" type="submit"><?php esc_html_e("Signup", BaseController::$text_domain); ?></button>
                        </div>
                    </form>
                </div>
                <div class="mxlms-card-footer mxlms-text-center">

                    <div class="mxlms-text-center">
                        <span class="mxlms-d-block mxlms-text-primary">
                            <a href="<?php echo esc_url_raw(Helper::get_url("page-contains=login")); ?>" class="mxlms-text-decoration-none mxlms-link-unset mxlms-font-12">
                                <i class="las la-arrow-left"></i>
                                <?php esc_html_e('Go to login page', BaseController::$text_domain); ?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>