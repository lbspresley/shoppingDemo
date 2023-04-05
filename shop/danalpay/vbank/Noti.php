<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_SHOP_PATH.'/settle_danalpay.inc.php');
include_once('./function.php');

/*
 * 결제 통지 데이터는 POST로 수신하며, KEY는 'DATA', VALUE는 '암호화 문자열' 이며 결제 정보 데이터를 포함하고 있습니다.
 * DATA 형식 : aes256( "BILLING_DATA" )
 * BILLING_DATA 형식 : KEY1=urlencode(VALUE1)[&KEY2=urlencode(VALUE2)...]
 *
 * 데이터 처리 방법
 * - POST 변수에서 'DATA'의 value를 읽어 옵니다.
 * - DATA 값을 복호화합니다.
 * - 복호화한 문자열은 결제 완료 정보를 포함하며, =&로 구분되는 key value pair 문자열입니다. (value-urlencoded)
 * - 해당 문자열을 =&로 구분(파싱)하여 사용할 수 있습니다.
*/

$RET_STR = $_POST['DATA']; // POST 변수에서 'DATA'의 value를 읽어 옵니다.

// mcyrpt 라이브러리 설치 여부 확인
if (function_exists("mcrypt_encrypt")) {
    $RET_STR = toDecrypt( $RET_STR ); // urldecode한 value를 복호화합니다.
}
else {
    $RET_STR = "**mcrypt library fail**";
}

// 로그기록
$log_txt = date('Y-m-d H:i:s', time());
$log_txt .= '|IP : '.getenv("REMOTE_ADDR");
foreach($_POST as $uk=>$uv) {
    $log_txt .= "|POST:".$uk."=".$uv;
}

foreach($_GET as $uk=>$uv) {
    $log_txt .= "|GET:".$uk."=".$uv;
}

$log_txt .= "|DECRYPT DATA=".$RET_STR;

$log_dir = G5_DATA_PATH.'/danalpaylog';

@mkdir($log_dir, G5_DIR_PERMISSION);
@chmod($log_dir, G5_DIR_PERMISSION);

wz_fwrite_log($log_dir."/query_noti_".date("Ymd").".log", $log_txt);

parse_str($RET_STR, $_POST);
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

$Amt            = $AMOUNT; // 금액
$TID            = $TID; // 거래번호
$MOID           = $ORDERID; // 주문번호
$AuthDate       = $TRANDATE.$TRANTIME; // 입금일시 (yyMMddHHmmss)
$ResultCode     = $RETURNCODE; // 결과코드 ('0000' 경우 입금통보)
$ResultMsg      = iconv_utf8($RETURNMSG); // 결과메시지
$VbankNum       = $VIRTUALACCOUNT; // 가상계좌번호
$FnCd           = $BANKCODE; // 가상계좌 은행코드
$VbankName      = iconv_utf8($BANKNAME); // 가상계좌 은행명
$VbankInputName = iconv_utf8($DEPOSITUSERNAME); // 입금자 명

/****************************************************************************
 * 결제 결과와는 상관없이 결제통지를 잘 받았다면 'OK'를 공백없이 회신해야 합니다.
*****************************************************************************/


if ($ResultCode == '0000') {

    $receipt_time = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $AuthDate);

    $sql = " select pp_id, od_id from {$g5['g5_shop_personalpay_table']} where pp_id = '$MOID' and pp_tno = '$TID' ";
    $row = sql_fetch($sql);

    $result = false;

    if($row['pp_id']) {
        // 개인결제 UPDATE
        $sql = " update {$g5['g5_shop_personalpay_table']}
                    set pp_receipt_price    = '$Amt',
                        pp_receipt_time     = '$receipt_time'
                    where pp_id = '$MOID'
                      and pp_tno = '$TID' ";
        sql_query($sql, false);

        if($row['od_id']) {

            // 주문서 UPDATE
            $sql = " update {$g5['g5_shop_order_table']}
                        set od_receipt_price = od_receipt_price + '$Amt',
                            od_receipt_time = '$receipt_time',
                            od_shop_memo = concat(od_shop_memo, \"\\n개인결제 ".$row['pp_id']." 로 결제완료 - ".$receipt_time."\")
                      where od_id = '{$row['od_id']}' ";
            $result = sql_query($sql, FALSE);
        }
    } else {
        // 주문서 UPDATE
        $sql = " update {$g5['g5_shop_order_table']}
                    set od_receipt_price = '$Amt',
                        od_receipt_time = '$receipt_time'
                  where od_id = '$MOID'
                    and od_tno = '$TID' ";
        $result = sql_query($sql, FALSE);
    }


    if($result) {
        if($row['od_id'])
            $od_id = $row['od_id'];
        else
            $od_id = $MOID;

        // 주문정보 체크
        $sql = " select count(od_id) as cnt
                    from {$g5['g5_shop_order_table']}
                    where od_id = '$od_id'
                      and od_status = '주문' ";
        $row = sql_fetch($sql);

        if($row['cnt'] == 1) {
            // 미수금 정보 업데이트
            $info = get_order_info($od_id);

            $sql = " update {$g5['g5_shop_order_table']}
                        set od_misu = '{$info['od_misu']}' ";
            if($info['od_misu'] == 0)
                $sql .= " , od_status = '입금' ";
            $sql .= " where od_id = '$od_id' ";
            sql_query($sql, FALSE);

            // 장바구니 상태변경
            if($info['od_misu'] == 0) {
                $sql = " update {$g5['g5_shop_cart_table']}
                            set ct_status = '입금'
                            where od_id = '$od_id' ";
                sql_query($sql, FALSE);
            }
        }

        die('OK');
    }
    else {

        die('FAIL');
    }
}