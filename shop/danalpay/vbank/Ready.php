<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
include_once(G5_SHOP_PATH.'/settle_danalpay.inc.php');
include_once('./function.php');

$od_id  = clean_xss_tags($_POST['orderid']);
$amount = (int)preg_replace('/:[0-9]+$/', '', $_POST['amount']);

$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$od_id' order by dt_time desc limit 1 ";
$row = sql_fetch($sql);

$od = unserialize(base64_decode($row['dt_data']));

$error_text = '';
$error_flag = false;
$good_mny = (int)$od['good_mny'];
if ($good_mny !== $amount) { 
    $error_text = '결제금액이 잘못되었습니다.';
    $error_flag = true;
} 

$uid = md5($od_id.$row['dt_time'].$REMOTE_ADDR);

set_session('ss_orderview_uid', $uid);
set_session('wz_order_id', $od_id);

/*[ 필수 데이터 ]***************************************/
$REQ_DATA = array();

/******************************************************
 *  RETURNURL 	: CPCGI페이지의 Full URL을 넣어주세요
 *  CANCELURL 	: BackURL페이지의 Full URL을 넣어주세요
 ******************************************************/
$RETURNURL = G5_SHOP_URL.'/danalpay/vbank/CPCGI.php'; 
$CANCELURL = G5_SHOP_URL.'/danalpay/Cancel.php';
$NOTIURL   = G5_SHOP_URL.'/danalpay/vbank/Noti.php';

/**************************************************
 * 결제 정보
**************************************************/
$REQ_DATA["ACCOUNTHOLDER"] = iconv('utf-8', 'euc-kr', $_POST["od_name"]);
$REQ_DATA["ORDERID"]       = $od_id;
$REQ_DATA["AMOUNT"]        = $good_mny;
$REQ_DATA["ITEMNAME"]      = iconv('utf-8', 'euc-kr', $_POST["itemname"]);
$REQ_DATA["EXPIREDATE"]    = $_POST["expiredate"];

/**************************************************
 * 고객 정보
**************************************************/
$REQ_DATA["USERID"]    = $member['mb_id'] ? $member['mb_id'] : 'guest'; // 사용자 ID
$REQ_DATA["USEREMAIL"] = $od['od_email']; // 소보법 email수신처
$REQ_DATA["USERAGENT"] = "PC"; // 2019-03-18 : 가상계좌일경우는 값이 다름.
if (is_mobile()) { 
    $REQ_DATA["USERAGENT"] = "MW";
} 

/**************************************************
 * URL 정보
**************************************************/
$REQ_DATA["CANCELURL"] = $CANCELURL;
$REQ_DATA["RETURNURL"] = $RETURNURL;
$REQ_DATA["NOTIURL"]   = $NOTIURL;

/**************************************************
 * 기본 정보
**************************************************/
$REQ_DATA["TXTYPE"]      = "AUTH";
$REQ_DATA["SERVICETYPE"] = "DANALVACCOUNT";

$RES_DATA = CallVAccount($REQ_DATA, false);
//$RES_DATA = CallVAccountExec($REQ_DATA, false); //curl_init() 함수 이용이 불가능할때, curl 바이너리를 호출(curl 설치 필요)
	
if ( $RES_DATA['RETURNCODE'] == "0000" && !$error_flag) {
?>

    <style>
    .btn-reload{cursor:pointer;display:inline-block;font-family:돋움,Dotum,Verdana,applegothic;font-size:11px;letter-spacing:-1px;color:#333;line-height:18px;height:18px;text-align:center;vertical-align:middle;box-shadow:#fff 1px 1px inset,#f7f7f7 -1px -1px inset,rgba(0,0,0,0.03) 0 1px;padding:0 3px;border-width:1px;border-style:solid;border-color:#d0d0d0;border-image:initial;border-radius:1px;background:#fbfbfb}
    </style>

    <div style="text-align:center;margin-top:100px;">
        <div><img src="../img/loading.gif" border=0 /></div>
        <div style="margin:10px 0 0;">
            <?php echo $default['de_admin_company_name'];?>
        </div>
        <div style="margin:10px 0 0;">
            Wait Please.....
        </div>
        <div style="margin:10px 0 0;">
            <input type="button" class="btn-reload" value="재시도하기" onclick="location.reload();" />
        </div>
    </div>

    <form name="frm" id="frm" method="post">
        <input type="hidden" name="STARTPARAMS" value="<?php echo $RES_DATA['STARTPARAMS'];?>">
        <input type="hidden" name="CIURL"  	    value="">
        <input type="hidden" name="COLOR"  	    value="">
    </form>

    <script type="text/javascript">
    <!--
        // 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
        function noRefresh()
        {
            /* CTRL + N키 막음. */
            if ((event.keyCode == 78) && (event.ctrlKey == true))
            {
                event.keyCode = 0;
                return false;
            }
            /* F5 번키 막음. */
            if(event.keyCode == 116)
            {
                event.keyCode = 0;
                return false;
            }
        }
        
        function fnSubmit(f) {
            var _action = '<?php echo $RES_DATA["STARTURL"]?>';		

            f.action = _action;
            f.method = "post";
            f.submit();
        }

        document.onkeydown = noRefresh ;

        window.onload = function() {
            fnSubmit(document.forms.frm);
        }

    //-->
    </script>

    <?php
}
else {

    if ($error_flag) { 
        $RETURNCODE = '99';
        $RETURNMSG  = $error_text;
        $BackURL    = "";
    } 
    else {
        $RETURNCODE = $RES_DATA['RETURNCODE'];
        $RETURNMSG  = iconv('euc-kr', 'utf-8', $RES_DATA['RETURNMSG']);
        $BackURL    = "";
    }

    include('../Error.php');
}

include_once(G5_PATH.'/tail.sub.php');