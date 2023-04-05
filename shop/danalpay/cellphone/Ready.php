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



$TransR = array();

/******************************************************
 ** 아래의 데이터는 고정값입니다.( 변경하지 마세요 )
 * Command      : ITEMSEND2
 * SERVICE      : TELEDIT
 * ItemCount    : 1
 * OUTPUTOPTION : DEFAULT
 ******************************************************/
$TransR['Command'] = 'ITEMSEND2';
$TransR['SERVICE'] = 'TELEDIT';
$TransR['ItemCount'] = '1';
$TransR['OUTPUTOPTION'] = 'DEFAULT';

/******************************************************
 *  ID          : 다날에서 제공해 드린 ID( function 파일 참조 )
 *  PWD         : 다날에서 제공해 드린 PWD( function 파일 참조 )
 *  CPNAME      : CP 명
 ******************************************************/
$TransR['ID'] = $g_conf_hp_site_cd;
$TransR['PWD'] = $g_conf_hp_mer_key;
$CPName = $default['de_admin_company_name'];

/******************************************************
 * ItemAmt      : 결제 금액( function 파일 참조 )
 *      - 실제 상품금액 처리시에는 Session 또는 DB를 이용하여 처리해 주십시오.
 *      - 금액 처리 시 금액변조의 위험이 있습니다.
 * ItemName     : 상품명
 * ItemCode     : 다날에서 제공해 드린 ItemCode
 ******************************************************/
$ItemAmt = $good_mny;
$ItemName = $_POST['itemname'];
$ItemCode = '1270000000';
$ItemInfo = MakeItemInfo( $ItemAmt, $ItemCode, $ItemName );
$TransR['ItemInfo'] = $ItemInfo;

/***[ 선택 사항 ]**************************************/
/******************************************************
 * SUBCP		: 다날에서 제공해드린 SUBCP ID
 * USERID		: 사용자 ID
 * ORDERID		: CP 주문번호
 * IsPreOtbill		: AuthKey 수신 유무(Y/N) (재승인, 월자동결제를 위한 AuthKey 수신이 필요한 경우 : Y)
 * IsSubscript		: 월 정액 가입 유무(Y/N) (월 정액 가입을 위한 첫 결제인 경우 : Y)
 ******************************************************/
$TransR['SUBCP'] = '';
$TransR['USERID'] = $member['mb_id'] ? $member['mb_id'] : 'guest'; // 사용자 ID
$TransR['ORDERID'] = $od_id;
$TransR['IsPreOtbill'] = 'N';
$TransR['IsSubscript'] = 'N';

/********************************************************************************
 *
 * [ CPCGI에 HTTP POST로 전달되는 데이터 ] **************************************
 *
 ********************************************************************************/

/***[ 필수 데이터 ]************************************/
$ByPassValue = array();

/******************************************************
 * BgColor      : 결제 페이지 Background Color 설정
 * TargetURL    : 최종 결제 요청 할 CP의 CPCGI FULL URL
 * BackURL      : 에러 발생 및 취소 시 이동 할 페이지의 FULL URL
 * IsUseCI      : CP의 CI 사용 여부( Y or N )
 * CIURL        : CP의 CI FULL URL
 ******************************************************/
$ByPassValue['BgColor'] = '00';
$ByPassValue['TargetURL'] = G5_SHOP_URL.'/danalpay/cellphone/CPCGI.php';
$ByPassValue['BackURL'] = G5_SHOP_URL.'/danalpay/Cancel.php';
$ByPassValue['IsUseCI'] = 'N';
$ByPassValue['CIURL'] = 'http://localhost/Danal/Teledit/images/ci.gif';

/***[ 선택 사항 ]**************************************/

/******************************************************
 * Email	: 사용자 E-mail 주소 - 결제 화면에 표기
 * IsCharSet	: CP의 Webserver Character set
 ******************************************************/
$ByPassValue['Email'] = $od['od_email'];
$ByPassValue['IsCharSet'] = 'utf-8';

/******************************************************
 ** CPCGI에 POST DATA로 전달 됩니다.
 **
 ******************************************************/
$ByPassValue['ByBuffer'] = '';
$ByPassValue['ByAnyName'] = '';

$Res = CallTeledit( $TransR,false );





if ( $Res['Result'] == '0' && !$error_flag ) {
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
        <?php
            MakeFormInput($Res,array('Result', 'ErrMsg'));
            MakeFormInput($ByPassValue);
        ?>
        <input type="hidden" name="CPName"      value="<?php echo $CPName?>">
        <input type="hidden" name="ItemName"    value="<?php echo $ItemName?>">
        <input type="hidden" name="ItemAmt"     value="<?php echo $ItemAmt?>">
        <input type="hidden" name="IsPreOtbill" value="<?php echo $TransR['IsPreOtbill']?>">
        <input type="hidden" name="IsSubscript" value="<?php echo $TransR['IsSubscript']?>">
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
            var _action = 'https://ui.teledit.com/Danal/Teledit/Web/Start.php';

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

    $Result		= $Res['Result'];
    $ErrMsg		= $Res['ErrMsg'];
    $AbleBack	= false;
    $BackURL	= $ByPassValue['BackURL'];
    $IsUseCI	= $ByPassValue['IsUseCI'];
    $CIURL		= $ByPassValue['CIURL'];
    $BgColor	= $ByPassValue['BgColor'];

    if ($error_flag) {
        $RETURNCODE = '99';
        $RETURNMSG  = $error_text;
        $BackURL    = "";
    }
    else {
        $RETURNCODE = $Result;
        $RETURNMSG  = iconv('euc-kr', 'utf-8', $ErrMsg);
        $BackURL    = "";
    }

    include('../Error.php');
}

include_once(G5_PATH.'/tail.sub.php');