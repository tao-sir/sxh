$(function () {
    $('.fix_hover01').mouseenter(function(){
        $('.fix-ewm').css('display','block');
    });
    $('.fix_hover01').mouseleave(function(){
        $('.fix-ewm').css('display','none');
    });

    $('.fix_hover02').mouseenter(function(){
        $('.fix-weibo').css('display','block');
    });
    $('.fix_hover02').mouseleave(function(){
        $('.fix-weibo').css('display','none');
    });

    $('.fix_hover03').mouseenter(function(){
        $('.fix-qq').css('display','block');
    });
    $('.fix_hover03').mouseleave(function(){
        $('.fix-qq').css('display','none');
    });


    $('.fix_hover04').mouseenter(function(){
        $('.fix-kf').css('display','block');
    });
    $('.fix_hover04').mouseleave(function(){
        $('.fix-kf').css('display','none');
    });


    //returnTop
    $(window).scroll(function (e) {
        if ($(window).scrollTop() > 100) {
            $('#returnTop').show();
        } else {
            $('#returnTop').hide();
        }
        $('#returnTop').click(function (e) {
            $('html,body').stop().animate({ 'scrollTop': '0px' }, 500);
        });

    });
})