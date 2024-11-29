;(function ($) {
    $(document).ready(function () {

        // email blur function
        $("#email").on('blur',function(){
            if($(this).val() == ''){
                return;
            }
            $('#first_name').val('');
            $('#last_name').val('');
            var email = $(this).val();
            $.post({
            
                url: qofw.ajaxurl,
                data : {
                    action: 'qofw_user_fetch',
                    nonce: qofw.nonce,
                    email: email
                },

                success: function(data){
                    data = JSON.parse(data);
                    if($('#first_name').val() == ''){
                        $('#first_name').val(data.fn);
                    }
                    if($('#last_name').val() == ''){
                        $('#last_name').val(data.ln);
                    }
                  
                    $("#phone").val(data.pn);
                    $("#customer_id").val(data.id);

                    if(!data.error){
                        if($('#first_name').val() !== ''){
                            $('#first_name').attr('readonly', 'readonly');
                        }
                        if($('#last_name').val() !== ''){
                            $('#last_name').attr('readonly', 'readonly');
                        }
                        if($('#phone').val() != ''){
                            $('#phone').attr('readonly', 'readonly');
                        }
                        $('#password_container').hide();
                    }else{
                        $('#first_name').removeAttr('readonly', 'readonly');
                        $('#last_name').removeAttr('readonly', 'readonly');
                        $('#phone').removeAttr('readonly', 'readonly');
                        $('#password_container').show();
                    }
                }
            });

        });
     
        // coupon and discount check
        $("#coupon").on('click', function () {
            if ($('#coupon').prop('checked')) {
                $("#discount-label").html(qofw.dc);
                $("#discount").attr("placeholder", qofw.cc);
            } else {
                $("#discount-label").html(qofw.dt);
                $("#discount").attr("placeholder", qofw.dt);
            }
        });

        // generate password
        $("#qofw_genpw").on('click', function(){
            $.post({
                url: qofw.ajaxurl,
                data: {
                    action: 'qofw_pass_generate',
                },
                success: function(response){
                    if(response.success){
                        $('#password').val(response.data);
                    }
                }
            })
        });

        // thikbox show
        if ($('#qofw-edit-button').length > 0) {
            tb_show(qofw.pt, "#TB_inline?inlineId=qofw-modal&width=700");
        }

    });
})(jQuery);
