<?php
/**
 * page file : /theme/THEME_NAME/page/contactus.html.php
 */
if (!defined('_EYOOM_')) exit;
?>

<style>
.sub-page p, .sub-page li {word-break:keep-all;font-size:13px}
.sub-title {position:relative;font-size:37px;color:#333;margin:10px 0 70px;font-weight:300}
.sub-title small {display:block;margin-top:10px;font-size:13px;border-top:1px solid #333;padding-top:10px}
.contact-info h3 {margin:0;padding-left:5px;font-size:15px;font-weight:bold}
.contact-info li {margin:3px 0;padding:5px;border-top:1px solid #aaa;color:#707070}
.contact-info li span {display:inline-block;width:70px;color:#333;font-weight:bold;margin-right:10px}
<?php if ($eyoom['is_responsive'] == '1' || G5_IS_MOBILE) { // 반응형 또는 모바일일때 ?>
@media (max-width:767px){
    .sub-title {margin-bottom:40px}
	.contact-img {margin-bottom:20px;max-height:300px;overflow:hidden}
}
<?php } ?>
</style>
<div class="sub-page page-contact">
<!--
	<h3 class="sub-title">Contact Us <small>Real Art, No Doubt. RealCollection.</small></h3>
-->
	<h3 class="sub-title">Contact Us <small>Welcome Audition-Me with your K-POP life!</small></h3>

	<div class="contact-top">
		<div class="contact-info">
			<div class="row">
				<div class="col-sm-5">
					<div class="contact-img"><img src="<?php echo EYOOM_THEME_PAGE_URL; ?>/img/contactus_01.jpg" alt="building" class="img-responsive"></div>
				</div>
				<div class="col-sm-7">
					<h3>회사정보</h3>
					<ul class="list-unstyled">
						<li><span>&middot; 주소</span> <?php echo $bizinfo['bi_company_zip']; ?> <?php echo $bizinfo['bi_company_addr1']; ?> <?php echo $bizinfo['bi_company_addr2']; ?> <?php echo $bizinfo['bi_company_addr3']; ?></li>
						<li><span>&middot; 이메일</span> <a href="mailto:<?php echo $bizinfo['bi_cs_email']; ?>"><?php echo $bizinfo['bi_cs_email']; ?></a></li>
						<li><span>&middot; 전화번호</span> <?php echo $bizinfo['bi_cs_tel1']; ?></li>
						<li><span>&middot; 팩스번호</span> <?php echo $bizinfo['bi_cs_fax']; ?></li>
					</ul>
					<div class="map-box">
<!-- * 카카오맵 - 지도퍼가기 -->
<!-- 1. 지도 노드 -->
<div id="daumRoughmapContainer1679408434032" class="root_daum_roughmap root_daum_roughmap_landing"></div>

<!--
	2. 설치 스크립트
	* 지도 퍼가기 서비스를 2개 이상 넣을 경우, 설치 스크립트는 하나만 삽입합니다.
-->
<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>

<!-- 3. 실행 스크립트 -->
<script charset="UTF-8">
	new daum.roughmap.Lander({
		"timestamp" : "1679408434032",
		"key" : "2e693",
		"mapWidth" : "640",
		"mapHeight" : "360"
	}).render();
</script>
						<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3162.323218901836!2d126.97694200000001!3d37.57100600000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x357ca2ec98800045%3A0xdd5786518f45a705!2z7J207Iic7Iug7J6l6rWw64-Z7IOB!5e0!3m2!1sko!2skr!4v1430703770386" width=100% height="300" frameborder="0" style="border:0"></iframe> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
