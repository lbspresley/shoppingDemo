<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_SHOP_PATH.'/settle_danalpay.inc.php');
include_once(G5_SHOP_PATH.'/danalpay/cellphone/function.php');

$ServerInfo = $_POST['RETURNPARAMS'];

$nConfirmOption = 1; 
$TransR["Command"] = "NCONFIRM";
$TransR["OUTPUTOPTION"] = "DEFAULT";
$TransR["ServerInfo"] = $ServerInfo;
$TransR["IFVERSION"] = "V1.1.2";
$TransR["ConfirmOption"] = $nConfirmOption;

if ( $nConfirmOption == 1 ) {
    $TransR["CPID"] = $g_conf_hp_site_cd;
    $TransR["AMOUNT"] = $_POST["good_mny"];
}

$Res = CallTeledit( $TransR, false );

if ($Res["Result"] == "0") {

    $TransR = array();

    $nBillOption = 0;
    $TransR["Command"] = "NBILL";
    $TransR["OUTPUTOPTION"] = "DEFAULT";
    $TransR["ServerInfo"] = $ServerInfo;
    $TransR["IFVERSION"] = "V1.1.2";
    $TransR["BillOption"] = $nBillOption;

    $Res2 = CallTeledit( $TransR, false );

    if( $Res2["Result"] != "0" )
    {
        $BillErr = true;
    }
}

if( $Res['Result'] == '0' && $Res2['Result'] == '0' ) { // 성공

    $tno        = $Res['TID'];
    $amount     = $Res['AMOUNT'];
    $app_time   = $Res2['DATE'];
    $bank_name  = '';
    $depositor  = '';
    $commid     = '';
    $mobile_no  = '';
    $app_no     = $Res['DNTID'];
    $card_name  = '';

}
else {
    
    if( $BillErr ) $Res = $Res2;

    $res_cd  = $Res['Result'];
    $res_msg = iconv('euc-kr', 'utf-8', $Res['ErrMsg']);

    alert("$res_cd : $res_msg");
    exit;
}