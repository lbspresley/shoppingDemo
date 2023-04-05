<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    /*****************************************************
	   * 다날 신용카드 매출전표 API
	*****************************************************/

	/******************************************************
	   * DN_RECEIPT_URL   : 매출전표 출력 URL
	******************************************************/
	$DN_RECEIPT_URL = "https://www.danalpay.com/receipt/creditcard/view.aspx";

	/*****************************************************
	   * 다날 신용카드 매출전표 암호화 샘플
	   * CPID  : 다날에서 부여해 드린 CPID
	   * CRYPTOKEY  : 다날에서 제공해 드린 암복호화 PW
	   * IVKEY : 암복호화에 사용되는 Initial Vector 값(고정값)
	******************************************************/
    $CPID = $g_conf_site_cd; // 실서비스를 위해서는 반드시 교체필요.
	$CRYPTOKEY = $g_conf_mer_key;// 암호화KEy. 실서비스를 위해서는 반드시 교체필요.

	$IVKEY = "d7d02c92cb930b661f107cb92690fc83";

	function toEncrypt($tid, $amount){
		global $CRYPTOKEY;
		global $IVKEY;

		$CRYPTOKEY = hextobin($CRYPTOKEY);
		$IVKEY = hextobin($IVKEY);
		
		$data = "TID=" . $tid . "|" . "AMOUNT=" . $amount;
		// 구분자 |를 사용해서 TID와 AMOUNT를 하나의 문자열로 묶음
		$data = addPKCS5($data);
		// pkcs7 padding

		$CIPHER = MCRYPT_RIJNDAEL_128;
		$MODE = MCRYPT_MODE_CBC;

		$encrypt_string = mcrypt_encrypt($CIPHER, $CRYPTOKEY, $data, $MODE, $IVKEY);

		$EncText = base64_encode($encrypt_string);
		$EncText = urlencode($EncText);

		return $EncText;
	}

	function addPKCS5( $str ){
		$stringsize = strlen( $str );
		$blocksize = mcrypt_get_block_size( MCRYPT_RIJNDAEL_128 , MCRYPT_MODE_CBC );

		$paddingsize = $blocksize - ( $stringsize % $blocksize );
		$paddingchar = chr($paddingsize);
		$str .= str_repeat( $paddingchar , $paddingsize);

		return $str;
	}

	function CallCreditAPI($data){
		global $DN_RECEIPT_URL;
		global $CPID;

		$REQ_STR = "?dataType=receipt";
		$REQ_STR = $REQ_STR . "&cpid=" . $CPID;
		$REQ_STR = $REQ_STR . "&data=" . $data;
		$REQ_STR = $DN_RECEIPT_URL . $REQ_STR;

		return $REQ_STR;
	}

	function hextobin($hexstr) 
	{ 
		$n = strlen($hexstr); 
		$sbin="";   
		$i=0; 
		while($i<$n) {       
			$a =substr($hexstr,$i,2);           
			$c = pack("H*",$a); 
			if ($i==0) {
				$sbin=$c;
			} else {
				$sbin.=$c;
			} 
			$i+=2; 
		} 
		return $sbin; 
	} 

    $tid        = $od_tno;
	$amount     = $od_receipt_price;
	$EncData    = toEncrypt($tid, $amount);
	$receipturl = CallCreditAPI($EncData);