<?php

function otp_menu()
{
    add_menu_page('Welcome to OTP', 'OTP', 'activate_plugins', 'otp', 'otp_welcome', 'dashicons-smartphone', 63);
    add_submenu_page('otp','Configure Account Information','Configure','activate_plugins','configure','otp_configuration',63);
}
add_action('admin_menu', 'otp_menu');