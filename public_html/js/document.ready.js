var __ = function(a, b, c) {
    $('body').on(a, b, c);
};
$(function() {

    var boxupBlur = function() {
        var $tool = $(this).parents('.tool');
        if($tool.hasClass('active')) {
            $('.tool').removeClass('active');
        } else {
            $('.tool').removeClass('active');
            var isLoaded = $tool.addClass('active').find('[tabindex="1"]');
            if(isLoaded.length > 0) {
                isLoaded.focus();
            } else {
                /*$.get($(this).attr('href'), function(res) {
                    $tool.find('.input-data-form').append(res.content);
                });*/
            }
        }
    };
    __('click', '.tool > a, .tool button[name="cancel"]', boxupBlur);
})