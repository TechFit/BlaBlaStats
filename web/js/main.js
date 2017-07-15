$(document).ready(function() {

    $(document).on('pjax:send', function() {
        $('body').append("<div class='loader'></div>");
        $('body .wrap').css('opacity', '0.1');
    })

    $(document).on('pjax:success', function() {
        $('div').remove(".loader");
        $('body .wrap').css('opacity', '');
    })
});