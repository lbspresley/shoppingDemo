<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_SHOP_PATH.'/settle_danalpay.inc.php');
include_once(G5_SHOP_PATH.'/danalpay/vbank/function.php');

$RES_STR = toDecrypt( $_POST['RETURNPARAMS'] ); // 복호화
$RET_MAP = str2data( $RES_STR );

$RET_RETURNCODE = $RET_MAP["RETURNCODE"];
$RET_RETURNMSG  = iconv('euc-kr', 'utf-8', $RET_MAP["RETURNMSG"]);

$RES_DATA = array();
if (is_null($RET_RETURNCODE) || $RET_RETURNCODE != "0000") { // returnCode가 없거나 또는 그 결과가 성공이 아니라면 실패 처리

    $res_cd  = $RET_RETURNCODE;
    $res_msg = $RET_RETURNMSG;

    alert("$res_cd : $res_msg");
    exit;
}
else { // 신용카드 인증 성공 시 결제 완료 요청

    $REQ_DATA                = array();
    $REQ_DATA["TID"]         = $RET_MAP["TID"];
    $REQ_DATA["AMOUNT"]      = $_POST["good_mny"]; // 최초 결제요청(AUTH)시에 보냈던 금액과 동일한 금액을 전송
    $REQ_DATA["TXTYPE"]      = "ISSUEVACCOUNT";
	$REQ_DATA["SERVICETYPE"] = "DANALVACCOUNT";

    $RES_DATA = CallVAccount($REQ_DATA, false);
	//$RES_DATA = CallVAccountExec($REQ_DATA, false); //curl_init() 함수 이용이 불가능할때, curl 바이너리를 호출(curl 설치 필요)
}

if ( $RES_DATA['RETURNCODE'] == "0000" ) {
    
    $tno        = $RES_DATA['TID'];
    $amount     = $RES_DATA['AMOUNT'];
    $app_time   = $RES_DATA['TRANSTIME'];
    $bankname   = iconv('euc-kr', 'utf-8', $RES_DATA['BANKNAME']);
    $depositor  = $RES_DATA['ACCOUNTHOLDER'];
    $va_date    = $RES_DATA['EXPIRETIME']; // 가상계좌 입금마감시간
    $account    = $RES_DATA['VIRTUALACCOUNT'];
    $app_no     = $RES_DATA['CARDAUTHNO'];
    $card_name  = iconv('euc-kr', 'utf-8', $RES_DATA['CARDNAME']);

}
else {
    
    $res_cd  = $RES_DATA['RETURNCODE'];
    $res_msg = iconv('euc-kr', 'utf-8', $RES_DATA['RETURNMSG']);

    alert("$res_cd : $res_msg");
    exit;
}