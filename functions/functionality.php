<?php

// [phone_number_form] use this shortcode to call the phone number form.
function otp_phonenumber_form_shortcode()
{
    $url = get_site_url() . '/wp-content/plugins/otp-api/assets/images/iraq.png';
    $form_header = '<div class="wrap">
                        <div class="phone_text" dir="rtl">
                            <p>ادخل رقم الهاتف:</p>
                        </div>
                       
                        <form action="" method="post" id="sign_up">';

    $phone_number = '<div class="form-group">
                        <div class="flag_container">
                            <div class="init_selected">
                                <div class="flag">
                                    <img src="'.$url.'">
                                </div>
                                <div class="country_code">+964</div>
                            </div>
                            <input type="text" id="mobile_code" class="form-control phone_number_input" placeholder="Phone Number" name="name" autocomplete="off">
                        </div>
                        <small style="color:red;" id="phone_validation"></small>
                    </div>';
    $site_url = get_site_url();
    $form_footer = '<div class="otp_verify_btn">
                        <a href='.$site_url.' type="button" id="back_otp_info" class="button back-btn" >Back</a>
                        <button type="button" id="submit_btn">
                            Verify
                            <div class="sp sp-circle" id="spinner"></div>
                        </button>
                        
                    </div>
                     </form> 
                     
                    <!-- Modal -->
                    <div class="modal fade" id="verify_number_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Enter code recived code via SMS</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Enter Code</span>
                                  </div>
                                  <input type="text" id="sms_code" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"> 
                            </div>
                            <small style="color:red;" id="code_verification"></small>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close_btn" data-dismiss="modal">Close</button>
                            <button type="button" class="btn verify_btn" id="verify_code_btn">
                                Verify Code
                                <div class="sp sp-circle" id="spinner_verify"></div>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>';

    $form = $form_header . $phone_number . $form_footer;
    return $form;
}
add_shortcode('phone_number_form', 'otp_phonenumber_form_shortcode');


// insert OTP record
function insert_otp_record($phone_number, $verfication_key){
    global $wpdb;
    $otp_table = $wpdb->prefix.'otp';

    $insert_record = $wpdb->insert($otp_table,array(
        'date' => date('Y-m-d H:i:s'),
        'phone_number' => $phone_number,
        'verfication_key' => $verfication_key,
        'status' => 0
    ));
    if(!$insert_record){
        $wpdb->query($wpdb->prepare(
           "UPDATE $otp_table SET verfication_key = %s WHERE phone_number = %s",
            $verfication_key,
            $phone_number
        ));
    }
}


// ajax call data
function otp_send_sms(){
    $phone_nb = $_POST['phone'];
    $verf_key = rand(100000, 999999);
    $from = "CardTestApp";
    $to = "964".$phone_nb;
    $text = "Your MITTO verification code is: ".$verf_key;
    $request_url = 'https://rest.mittoapi.com/sms?from='.$from.'&to='.$to.'&text='.$text;

    $key = get_option('otp_api_key');

    insert_otp_record($to,$verf_key);

    // Request Session
    $response_json = wp_remote_post($request_url,array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'X-Mitto-API-Key' => $key
        )
    ));

    if(is_wp_error($response_json)){
        wp_send_json("Wordpress failed to connect with MITTO!");
    }

    $response = json_decode($response_json['body'],true);
    wp_send_json($response);
}
add_action('wp_ajax_nopriv_otp_send_sms','otp_send_sms');
add_action("wp_ajax_otp_send_sms", "otp_send_sms");

// ajax call verify code
function otp_verify_sms(){
    global $wpdb;
    $code = $_POST['code'];
    $phone_number = "964".$_POST['phone_number'];
    $otp_table = $wpdb->prefix.'otp';

    $result = $wpdb->get_row("SELECT verfication_key FROM $otp_table WHERE phone_number = $phone_number");
    $db_key = $result->verfication_key;
    if($code == $db_key){
        wp_send_json(
            array(
                'status'=>200
            )
        );
    }else{
        wp_send_json(
            array(
                'status'=>500
            )
        );
    }
}
add_action('wp_ajax_nopriv_otp_verify_sms','otp_verify_sms');
add_action("wp_ajax_otp_verify_sms", "otp_verify_sms");


// ajax call otp update status
function otp_update_status(){
    global $wpdb;
    $phone_number = "964".$_POST['phone_number'];
    $otp_table = $wpdb->prefix.'otp';

    $result = $wpdb->query($wpdb->prepare(
        "UPDATE $otp_table SET status = %s WHERE phone_number = %s",
        1,
        $phone_number
    ));
    wp_send_json($result);
}
add_action('wp_ajax_nopriv_otp_update_status','otp_update_status');
add_action("wp_ajax_otp_update_status", "otp_update_status");