<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');

$RETURNPARAMS = $_POST['RETURNPARAMS'];

if ( $RETURNPARAMS ) {

    $ordr_id = get_session('wz_order_id');
    $sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$ordr_id' ";
    $row = sql_fetch($sql);
    $data = unserialize(base64_decode($row['dt_data']));

    if(is_mobile()) { // mobile
        
        // 제외할 필드
        $exclude = array('req_tx', 'res_cd', 'tran_cd', 'buyr_name', 'buyr_tel1', 'buyr_tel2', 'buyr_mail', 'enc_info', 'enc_data', 'use_pay_method', 'rcvr_name', 'rcvr_tel1', 'rcvr_tel2', 'rcvr_mail', 'rcvr_zipx', 'rcvr_add1', 'rcvr_add2', 'param_opt_1', 'param_opt_2', 'param_opt_3', 'RETURNPARAMS');

        if(isset($data['pp_id']) && $data['pp_id']) {
            $order_action_url = G5_HTTPS_MSHOP_URL.'/personalpayformupdate.php';
        } else {
            $order_action_url = G5_HTTPS_MSHOP_URL.'/orderformupdate.php';
        }

        echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

        echo make_order_field($data, $exclude);

        echo '<input type="hidden" name="RETURNPARAMS" value="'.$RETURNPARAMS.'">'.PHP_EOL;

        echo '</form>'.PHP_EOL;

        ?>

        <script type="text/javascript">
        <!--
            setTimeout( function() { document.forderform.submit(); }, 300);
        //-->
        </script>

        <?php

    } 
    else { // pc

        if (isset($data['pp_id']) && $data['pp_id']) {
            $order_action_url = G5_HTTPS_SHOP_URL.'/personalpayformupdate.php';
        }
        else {
            $order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.php';
        }
        ?>

        <script type="text/javascript">
            <!--
            var f = window.parent.document.forderform;

            f.RETURNPARAMS.value = "<?php echo $RETURNPARAMS?>";
            f.action = "<?php echo $order_action_url?>";
            f.target = "_self";
            f.submit();

            $(top.document).find('.dimm').hide();
            $(top.document).find('#wzpaywin').hide();
            $(top.document).find('html').css('overflow-y','auto');

        //-->
        </script>
        <?php
    }

}
else {
    $RETURNCODE = '99';
    $RETURNMSG  = '리턴값이 없습니다.';
    $BackURL    = "";
    
    include('../Error.php');
}

include_once(G5_PATH.'/tail.sub.php');