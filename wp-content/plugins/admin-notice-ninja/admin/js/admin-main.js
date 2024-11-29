(function ($) {
    $(document).ready(function () {
        $("body").on("click", "#ann_notice .notice-dismiss", function () {
        setCookie("ann_close_notice", 1, 1);
        });
    });
})(jQuery);

function setCookie(cname, cvalue, exhours) {
    const d = new Date();
    d.setTime(d.getTime() + exhours * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
