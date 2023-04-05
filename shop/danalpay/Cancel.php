<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');

if (is_mobile()) { // 모바일결제는 결제주문화면으로 전환.

    $ordr_id = get_session('wz_order_id');
    $sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$ordr_id' ";
    $row = sql_fetch($sql);
    $data = unserialize(base64_decode($row['dt_data']));

    if(isset($data['pp_id']) && $data['pp_id']) {
        $page_return_url = G5_SHOP_URL.'/personalpayform.php?pp_id='.get_session('ss_personalpay_id');
    } else {
        $page_return_url = G5_SHOP_URL.'/orderform.php';
        if(get_session('ss_direct'))
            $page_return_url .= '?sw_direct=1';
    }

    goto_url($page_return_url);
} 
else { // 피씨결제는 레이어창 닫음.
?>

<script type="text/javascript">
<!--
	$(function(){
		$(top.document).find('.dimm').hide();
		$(top.document).find('#wzpaywin').hide();
		$(top.document).find('html').css('overflow-y','auto');
	});
//-->
</script>

<?php } ?>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>