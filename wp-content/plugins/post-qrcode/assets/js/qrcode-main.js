;(function($){
    const inputVal = $('#qrcode_toggle').val();
    
    $('.toggle').minitoggle({
        on: inputVal =='true' ? true : false
    });

    $('.toggle').on('toggle', function(e){
        console.log(e.isActive);
        if(e.isActive){
            $('#qrcode_toggle').val('true');
        }else{
            $('#qrcode_toggle').val('');
        }
    });

    if(inputVal == 'true'){
        $('#qrcodeToggle .toggle-handle').attr('style', 'transform: translate3d(36px, 0px, 0px)');
    }
    
})(jQuery);