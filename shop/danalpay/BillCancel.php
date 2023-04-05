<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

/**************************
* 1. 라이브러리 인클루드 *
**************************/
require_once(G5_SHOP_PATH.'/settle_danalpay.inc.php');

$REQ_DATA = array();

if ($od_settle_case == '계좌이체' || $od_settle_case == '신용카드') { 
    
    $REQ_DATA['TID']             = $tno; // 결제 완료 TID
    $REQ_DATA['CANCELTYPE']      = 'C';
    $REQ_DATA['AMOUNT']          = $amount;
    $REQ_DATA['CANCELREQUESTER'] = $member['mb_id'] ? $member['mb_id'] : 'guest';
    $REQ_DATA['CANCELDESC']      = 'Cancel';
    $REQ_DATA['TXTYPE']          = 'CANCEL';

    if ($od_settle_case == '계좌이체') { 
        require_once(G5_SHOP_PATH.'/danalpay/dbank/function.php');
        $REQ_DATA['SERVICETYPE']    = 'WIRETRANSFER';
        $RES_DATA = CallCredit($REQ_DATA, false);
    } 
    else {
        require_once(G5_SHOP_PATH.'/danalpay/card/function.php');
        $REQ_DATA['SERVICETYPE']    = 'DANALCARD';
        $RES_DATA = CallCredit($REQ_DATA, false);
    }

$res_cd     = $RES_DATA['RETURNCODE'];
$res_msg    = iconv_utf8($RES_DATA['RETURNMSG']);

} 
else if ($od_settle_case == '휴대폰') { 

    require_once(G5_SHOP_PATH.'/danalpay/cellphone/function.php');

	$TransR = array();

	/******************************************************
	 * ID		: 다날에서 제공해 드린 ID( function 파일 참조 )
	 * PWD		: 다날에서 제공해 드린 PWD( function 파일 참조 )
	 * TID		: 결제 후 받은 거래번호( TID or DNTID )
	 ******************************************************/
	$TransR["ID"] = $g_conf_hp_site_cd;
	$TransR["PWD"] = $g_conf_hp_mer_key;
	$TransR["TID"] = $tno;
	
	/***[ 고정 데이터 ]*************************************
	 * Command	: BILL_CANCEL
	 * OUTPUTOPTION	: 3
	 ******************************************************/
	$TransR["Command"] = "BILL_CANCEL";
	$TransR["OUTPUTOPTION"] = "3";

	$Res = CallTeledit($TransR, false);

	if ($Res["Result"] == "0") {

        $res_cd     = '0000';
        $res_msg    = '';
	}
	else {
		$res_cd     = '9999';
        $res_msg    = '취소실패';
	}
}