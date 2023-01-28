<?php

function otp_configuration(){
    $otp_api_key = get_option('otp_api_key');
    $otp_username = get_option('otp_username');
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div>
            <form name="otp_configure_data" method="post" action="<?php echo esc_html(admin_url('admin.php?page=configure')); ?>">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="otp_username">Username</label></th>
                            <td>
                                <input name="otp_username" type="text" id="" aria-describedby="tagline-description" class="regular-text" value="<?php echo $otp_username; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="otp_api_key">API Key</label></th>
                            <td>
                                <input name="otp_api_key" type="text" id="" aria-describedby="tagline-description" class="regular-text" value="<?php echo $otp_api_key; ?>">
                                <p class="description" id="home-description">API KEY like: Hsjf3jgffhGtDRyj2e5barmKcH.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="otp_submit" id="save_otp_info" class="button button-primary" value="Save Data">
                </p>
            </form>
        </div>
    </div>
 <?php }

    // Inserting Data to options table
    if (isset($_POST['otp_submit'])) {
        if (!empty($_POST['otp_api_key'])) {
            $otp_api_key_value = $_POST['otp_api_key'];
            $otp_username_value = $_POST['otp_username'];

            update_option('otp_api_key', $otp_api_key_value);
            update_option('otp_username', $otp_username_value);
            otp_admin_post_redirect();
        }
    }

    // Redirecting function
    function otp_admin_post_redirect()
    {
        header("location: " . $_SERVER['REQUEST_URI']);
    }





