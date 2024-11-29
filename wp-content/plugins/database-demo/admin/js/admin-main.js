;(function($){
    jQuery(document).ready(function () {
   
        $(".person-delete").on("click", function (e) {
            $('#message').html('');
            console.log('hello');
            var parent = $(this).parents('tr');
            

            if(confirm(dbdemo_vars.confirm_message)){
                var person_id =  $(this).data('id');
                $.ajax({
                    url:dbdemo_vars.ajaxurl,
                    type:'POST',
                    data:{
                        action: 'delete_person',
                        person_id: person_id,
                        security: dbdemo_vars.nonce
                    },
                    success: function(response){
                        console.log(response);
                        if(response.success){
                            $("#person-" + person_id).remove();
                            $(parent).remove();
                            $('#message').append('<div class="updated mt-3"><p>Deleted Successfully!</p></div>');
                            setTimeout(function(){
                                $('#message').html('');
                            }, 2000);
                        }
                    }
                })
            }
        });
    });
    
})(jQuery);

