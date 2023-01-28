<?php

function otp_welcome(){ ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div>
            <h2 class="title">Paste shortcode in frontend page:</h2>
            <p>Shortcode: [phone_number_form] </p>
        </div>
    </div>
<?php }