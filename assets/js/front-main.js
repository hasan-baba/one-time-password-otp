jQuery(document).ready(function(){

    // function check mobile number
    function check_mobile_number(number){
        var pattern = /^[0-9]{10}$/;
        let result = pattern.test(number);
        return result;
    }

    // styling the front page
    jQuery("#phone_number").css("display", "none");
    jQuery("#id_request_form_btn").click(function(){
        jQuery("#phone_number").css("display", "block");
        jQuery("#general-info").css("display", "none");
    });


    jQuery("#introductory_form_btn").click(function(){
        jQuery("#phone_number").css("display", "block");
        jQuery("#general-info").css("display", "none");
    });
    var link;
    jQuery(".home_btn_otp").click(function(){
       link = jQuery(this).attr("id");
    });

    //onchange in input, the value will be checked
    jQuery("#verify_number_modal").modal('hide');
    let input = jQuery("#mobile_code");
    jQuery("#submit_btn").on('click', function () {
       if(check_mobile_number(input.val()) == false){
           jQuery("#phone_validation").text("Enter 10 numbers only! ");
           jQuery("#verify_number_modal").modal('hide');
       }else{
           jQuery("#phone_validation").text("");
           var phone_number = input.val();
           jQuery.ajax({
              type: 'POST',
              url: ajaxurl,
              dataType: 'json',
              data: {
                  action: 'otp_send_sms',
                  phone: phone_number
              },
               beforeSend:function(){
                   jQuery("#spinner").css('display','inline-block');
                   // jQuery("#verify_code_btn").attr("disabled", true);
               },
               success: function(data){
                   jQuery("#verify_number_modal").modal('show');
                   jQuery("#spinner").css('display','none');
               }
           });

       }
    });


    // verify SMS code
    jQuery("#verify_code_btn").on("click",function(){
        let code = jQuery("#sms_code").val();
        let phone_number = jQuery("#mobile_code").val();

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: 'otp_verify_sms',
                code: code,
                phone_number: phone_number
            },
            beforeSend:function(){
                jQuery("#spinner_verify").css('display','inline-block');
                jQuery("#verify_code_btn").attr("disabled", true);
            },
            success: function(data){
                jQuery("#spinner_verify").css('display','none');
                jQuery("#verify_code_btn").attr("disabled", false);
                if(data['status']==200){
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        dataType: 'json',
                        data: {
                            action: 'otp_update_status',
                            phone_number: phone_number
                        },
                        success: function(data){
                            if(link == "id_request_form_btn"){
                                window.location.href="/vvip-استمارة-هوية/";
                            }else{
                                window.location.href="/استمارة-الحصول-على-الهوية-التعريفية/";
                            }

                            jQuery("#code_verification").text("");
                        }
                    });
                }else{
                    jQuery("#code_verification").text("Invalid Code!");
                }
            }
        });
    });
})
