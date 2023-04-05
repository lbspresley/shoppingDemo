<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<input type="hidden" name="good_mny"        value="<?php echo $tot_price; ?>"> <!-- 영카트 로직을 위한 값 -->

<!-- Mall Parameters : 필수 --> 
<input type="hidden" name="paymethod"           value="">
<input type="hidden" name="orderid"             value="<?php echo $od_id;?>"> <!-- 상품주문번호 -->
<input type="hidden" name="itemname"            value="<?php echo $goods;?>"> <!-- 결제상품명 -->
<input type="hidden" name="amount"              value="<?php echo $tot_price;?>"> <!-- 결제상품금액 -->
<input type="hidden" name="useragent"           value="WP">
<input type="hidden" name="expiredate"          value="<?php echo $expiredate;?>">
<input type="hidden" name="UserIP"              value="<?php echo $_SERVER['REMOTE_ADDR'];?>"> <!-- 회원사고객IP -->
<input type="hidden" name="MallIP"              value="<?php echo ($_SERVER['SERVER_ADDR']?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR']);?>"> <!-- 상점서버IP -->

<input type="hidden" name="RETURNPARAMS"        value="">