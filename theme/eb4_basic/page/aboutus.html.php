<?php
/**
 * page file : /theme/THEME_NAME/page/aboutus.html.php
 */
if (!defined('_EYOOM_')) exit;
?>

<style>
.sub-page p, .sub-page li {word-break:keep-all;font-size:13px}
.sub-title {position:relative;font-size:37px;color:#333;margin:10px 0 70px;font-weight:300}
.sub-title small {display:block;margin-top:10px;font-size:13px;border-top:1px solid #333;padding-top:10px}
.aboutus-top {position:relative}
.aboutus-img {position:absolute;top:50px;left:50px;max-height:480px}
.aboutus-img:before {content:"";position:absolute;display:block;width:150px;height:150px;left:200px;bottom:-25px;background:#BD081C;z-index:1}
.aboutus-img img {position:relative;display:block;width:320px;height:auto;z-index:2}
.aboutus-top .text-1 {padding:50px 50px 50px 430px;margin:0;color:#fff;background:#2B3749}
.aboutus-top .text-2 {padding:50px 50px 50px 430px;margin:0;color:#000;background:#DADFE5}
.page-words {margin:70px 0}
.page-words h4 {font-size:24px;line-height:28px;font-weight:bold;color:#34608D;text-align:center}
<?php if ($eyoom['is_responsive'] == '1' || G5_IS_MOBILE) { // 반응형 또는 모바일일때 ?>
@media (min-width:992px) and (max-width:1199px){
	.aboutus-top .text-2 {padding:150px 50px 50px}
}
@media (min-width:768px) and (max-width:991px){
	.aboutus-top .text-2 {padding:170px 50px 50px}
}
@media (max-width:767px) {
    .sub-title {margin-bottom:40px}
	.page-words {margin:40px 0}
	.page-words h4 {font-size:16px;line-height:22px}
	.aboutus-img {top:20px;left:20px}
	.aboutus-img:before {left:165px;bottom:-15px}
	.aboutus-img img {width:300px}
	.aboutus-top .text-1 {padding:360px 20px 20px}
	.aboutus-top .text-2 {padding:20px}
}
<?php } ?>
</style>

<!-- 20230405 : change about us -->
<div class="sub-page page-aboutus">
	<h3 class="sub-title">About Us <small>K-POP 글로벌 '팬덤 프로슈머' 플랫폼</small></h3>
	 
	<div class="aboutus-top" style="width:100%" >
	<img src="<?php echo EYOOM_THEME_PAGE_URL; ?>/img/aboutus_new.jpeg" style="width: 100%" alt="About Us" />
</div>
<!-- 20230405 : change about us -->

<!-- 20230405 : change about us 
<div class="sub-page page-aboutus">
	<h3 class="sub-title">About Us <small>Physical Art 컨텐츠 기반
전시/유통 플랫폼
‘RealCollection(리얼컬렉션)’
</small></h3>

	<div class="aboutus-top">
		<div class="aboutus-img">
			<img src="<?php echo EYOOM_THEME_PAGE_URL; ?>/img/aboutus_01.jpg" alt="" />
		</div>
		<p class="text-1">
팝아트, 그래피티 등 아트 컨텐츠 기반 유통 플랫폼<br><br>
대중성 및 작품성이 보장된 팝아트, 그래피티 Physical Art 컨텐츠 전시 및 유통과 P2P 마켓플레이스 거래를 통한 리셀 아트테크가 가능한 유통 플랫폼<br><br>
01. 콘텐츠 : IP독점권 | 디지털원본 | 소실 작품 복원 <br>
02. 기술 : 디지털원본의 안전한 보관<br>
03. 사업 : 리셀 아트테크 | 굿즈 등 확장성

</p>
		<p class="text-2">
✔ 우수성 및 작품성 기반의 IP 독점권 확보<br>
· 상품성 : 레전드, 현존하는 유명 작가 작품<br>
· 작품성 : 아트 디렉터의 심사/승인<br>
· 희소성 : 소실된 작품 복원, 디지털 원본<br>
· 대중성 : 다수의 에디션화 판매<br><br>

✔ 팬덤 형성을 위한 마케팅 시스템<br>
· 대중성을 고려한 글로벌 SNS 채널 마케팅 시스템 제공 : SNS 채널 플랫폼 운영자 운영<br>
· 2차 마켓 구매자에 대한 리워드 제공 : 구매자에 로열티 지급으로 활성화<br><br>


		</p>
	</div>
-->

	<div class="page-words">
<!--
		<h4>"Real Art, No Doubt. RealCollection."</h4>
-->
		<h4>"Welcome Audition-Me with your K-POP life!"</h4>
	</div>

	<div class="aboutus-bottom">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>회사명</td>
                    <td><strong><?php echo $bizinfo['bi_company_name']; ?></strong></td>
                </tr>
                <tr>
                    <td>대표</td>
                    <td><?php echo $bizinfo['bi_company_ceo']; ?></td>
                </tr>
                <tr>
                    <td>사업자등록번호</td>
                    <td><?php echo $bizinfo['bi_company_bizno']; ?></td>
                </tr>
                <tr>
                    <td>통신판매업신고번호</td>
                    <td><?php echo $bizinfo['bi_company_sellno']; ?></td>
                </tr>
                <tr>
                    <td>주소</td>
                    <td><?php echo $bizinfo['bi_company_zip']; ?> <?php echo $bizinfo['bi_company_addr1']; ?> <?php echo $bizinfo['bi_company_addr2']; ?> <?php echo $bizinfo['bi_company_addr3']; ?><a href="<?php echo G5_URL; ?>/page/?pid=contactus" class="btn-e btn-e-xs btn-e-default margin-left-5">상세지도</a></td>
                </tr>
                <tr>
                    <td>이메일</td>
                    <td><a href="mailto:<?php echo $bizinfo['bi_cs_email']; ?>"><?php echo $bizinfo['bi_cs_email']; ?></a></td>
                </tr>
                <tr>
                    <td>전화번호</td>
                    <td><?php echo $bizinfo['bi_cs_tel1']; ?></td>
                </tr>
            </tbody>
        </table>
	</div>
</div>
