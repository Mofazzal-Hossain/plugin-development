(function ($) {
    $(document).ready(function () {
        var slider = tns({
            container: '#tinySlider',
            items: 1,
            slideBy: 'page',
            mouseDrag: true,
            swipeAngle: false,
            speed: 400,
            nav: false,
        });
    });
})(jQuery);