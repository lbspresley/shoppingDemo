<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<style type="text/css">
.dimm{position:absolute;left:0;top:0;z-index:9000;background-color:#000;display:none}
#boxes .window{position:absolute;left:0;top:0;width:440px;height:200px;display:none;z-index:9999;padding:20px}
#boxes #wzpaywin{padding:0;margin:0;background-color:#fff}
</style>

<script type="text/javascript">
<!--
$(function(){
	$('body').append('<div id="boxes"><div id="wzpaywin" class="window"></div><div class="dimm"></div></div>');
});
function confirmEvent(f) {

    var paymethod = f.paymethod.value;
    if (paymethod == 'dbank') {
        var i_width = 790;
        var i_height = 590;
    }
    else if (paymethod == 'cellphone') {
        var i_width = 520;
        var i_height = 700;
    }
    else {
        var i_width = 690;
        var i_height = 490;
    }

    var id = '#wzpaywin';

    var maskHeight = $(document).height();
    var maskWidth = $(window).width() + 17;

    $('.dimm').css({'width':maskWidth,'height':maskHeight});
    $('.dimm').fadeIn(300);      	 
    $('.dimm').fadeTo('fast',0.8);	

    var leftP = ( $(window).scrollLeft() + ($(window).width() - i_width) / 2 );
    var topP = ( $(window).scrollTop() + ($(window).height() - i_height) / 2 );
          
    $(id).css('top',  topP);
    $(id).css('left', leftP);

    $(id).fadeIn(1000); //페이드인 속도..숫자가 작으면 작을수록 빨라집니다.
    if (paymethod == 'dbank') {
        $(id).html('<iframe width="800" height="600" src="" name="pay_iframe" id="pay_iframe" frameborder="0">이 브라우저는 iframe을 지원하지 않습니다</iframe>');
    }
    else if (paymethod == 'cellphone') {
        $(id).html('<iframe width="520" height="700" src="" name="pay_iframe" id="pay_iframe" frameborder="0">이 브라우저는 iframe을 지원하지 않습니다</iframe>');
    }
    else {
        $(id).html('<iframe width="700" height="500" src="" name="pay_iframe" id="pay_iframe" frameborder="0">이 브라우저는 iframe을 지원하지 않습니다</iframe>');
    }

    $('html').css('overflow-y','hidden');

    f.action = g5_url+"/shop/danalpay/"+paymethod+"/Ready.php";
    f.target = "pay_iframe";
    f.submit();
}
//-->
</script>