; (function ($) {
    $(document).ready(function () {
        $(".action-button").on('click', function () {
            let task = $(this).data('task');
            window[task]();
        });
    });
})(jQuery);


function simple_ajax_call(){
    let $ = jQuery;
    $('.plugin-result').text('');
    $.post({
        url: plugindata.ajaxurl,
        data:{
            action: 'simple_ajax_call',
            simple: 'Hello Ajax',
        },
        success: function(response){
            if (response.success) {
                $('.plugin-result').show();
                $('.plugin-result').text(response.data);
            }
        }
    });
}


function unprivileged_ajax_call(){
    let $ = jQuery;
    $('.plugin-result').text('');
    $.post({
        url: plugindata.ajaxurl,
       
        data: {
            action: 'privileged_ajax_call',
            simple: "Unprivileged Ajax Call",
        },
        success: function(response){
            if(response.success){
                console.log(response.data);
                $('.plugin-result').show();
                $('.plugin-result').text(response.data);
            }
        }
    })
}

function wp_localize_script(){
    let $ = jQuery;
    var name = personData.Name;
    var email = personData.Email;
    var age = personData.Age;
    var data = "<strong>Name:</strong> " + name + '<br> <strong>Email</strong>: ' + email + "<br> <strong>Age</strong>: " + age;

   $('.plugin-result').show();
   $('.plugin-result').html(data);
}

function wp_secure_ajax_call(){
    let $ = jQuery;
    
    $.post({
        url: secureData.ajaxurl,
        data: {
            action: 'secure_ajax_call',
            nonce: secureData.nonce,
            simple: "Hello, This is secure ajax call",
        },
        success: function(response){
            if(response.success){
        
                $('.plugin-result').show();
                $('.plugin-result').html(response.data);
            } else {
                alert('An error occurred: ' + response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error(`Error: ${status}, ${error}`);
        }
    })
}