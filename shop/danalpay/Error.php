<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');

if(trim($BackURL) == "") $BackURL = "javascript:closeFrame();";
?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo G5_SHOP_URL;?>/danalpay/css/style.css?ver=17102301" />
<script type="text/javascript">
<!--
    function closeFrame() {
        $(top.document).find('.dimm').hide();
		$(top.document).find('#wzpaywin').hide();
		$(top.document).find('html').css('overflow-y','auto');
    }
//-->
</script>

<div class="popWrap">
	<h1 class="logo"><img src="<?php echo G5_SHOP_URL;?>/danalpay/img/logo.gif" alt="Danal 신용카드" /></h1>
	<div class="tit_area">
		<p class="tit"><img src="<?php echo G5_SHOP_URL;?>/danalpay/img/tit06.gif" width="198" height="20" alt="결제서비스 에러 Service Error" /></p>
	</div>
	<div class="box">
		<div class="boxTop">
			<div class="boxBtm">
				<div class="service_error">
					<dl class="error01">
						<dt><img src="<?php echo G5_SHOP_URL;?>/danalpay/img/error_txt01.gif" width="58" height="14" alt="에러 내용" /></dt>
						<dd>[<?=$RETURNCODE?>]&nbsp;<?=$RETURNMSG?></dd>
					</dl>	
					<dl class="error02">
						<dt><img src="<?php echo G5_SHOP_URL;?>/danalpay/img/error_txt02.gif" width="58" height="14" alt="처리방법" /></dt>
						<dd>다날 고객센터 : 1566-3355 (전국공통)<br/>
						상담원 통화가능시간 : 평일 9시~ 18시 (토요일,일요일,공휴일 휴무)</dd>
					</dl>					
				</div>					
			</div>
		</div>
	</div>
	<p class="btn">
		<a href="<?php echo $BackURL?>"><img src="<?php echo G5_SHOP_URL;?>/danalpay/img/btn_confirm.gif" width="91" height="28" alt="확인" /></a>
	</p>
	<div class="popFoot">
		<div class="foot_top">
			<div class="foot_btm">
				<div class="noti_area">
					다날 신용카드결제를 이용해주셔서 감사합니다. [Tel] 1566-3355
				</div>
			</div>
		</div>			
	</div>
</div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>