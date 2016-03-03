<!DOCTYPE HTML>
<html>
<?php include_once "./theme/desktop/head.php"; ?>
<body>
    <div class="global-wrap">
        <?php include_once "./theme/desktop/header.php"; ?>
		<?php 
			if(count($keys)==0) {
				include_once "./theme/desktop/featured-albums.php"; 
			}
		?>
        <div class="container">
			<?php
			if(isset($contentPage) && file_exists($contentPage)) {
				include_once "$contentPage";
			} else {
				include_once "./theme/desktop/most-favorite-albums.php";
			}
			?>
        </div>
        <?php include_once "./theme/desktop/footer.php"; ?>
		<!-- Scripts queries -->
        <script src="/theme/js/jquery.js"></script>
        <script src="/theme/js/boostrap.min.js"></script>
        <script src="/theme/js/countdown.min.js"></script>
        <script src="/theme/js/flexnav.min.js"></script>
        <script src="/theme/js/magnific.js"></script>
        <script src="/theme/js/tweet.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/theme/js/fitvids.min.js"></script>
        <script src="/theme/js/mail.min.js"></script>
        <script src="/theme/js/ionrangeslider.js"></script>
        <script src="/theme/js/icheck.js"></script>
        <script src="/theme/js/fotorama.js"></script>
        <script src="/theme/js/owl-carousel.js"></script>
        <script src="/theme/js/masonry.js"></script>
        <script src="/theme/js/nicescroll.js"></script>
		<?php
		if(isset($downloadSong) && is_bool($downloadSong) && $downloadSong) {
			$songData=trim(strtolower($myData["song"]["album_slug"]))."/".trim(strtolower($myData["song"]["song_slug"]))."/".$myData["song"]["kbps"]."/";
			$fakeFile="[HQMusic.in] ".$myData["song"]["song_name"]." - ".$myData["song"]["album_name"]." ".$myData["song"]["kbps"]."".""."Kbps";
			$link="/$songData".getIPHash($songData)."/$fakeFile.mp3";
		?>
		<script>
		$(document).ready(function() {
			$.startDownloader=function(i) {
				if(i==0) {
					document.location="<?=$link?>";
					$(".dwnl-post center").html("<a class='btn btn-primary' href='<?=$link?>'><b>Thank you</b><br/><< Click Here to Download >></a>");
				} else {
					$(".dwnl-post #tab-downloader h1").html(i+"s");
					setTimeout(function() {$.startDownloader(i-1);},1000);
				}
			};
			$.startDownloader(10);
		});
		</script>
		<?php
		}
		?>
		
        <!-- Custom scripts -->
		<script>
		$.submitRC=function(form) {
			var name=$.trim($(form).find("#name").val());
			var email=$.trim($(form).find("#email").val());
			var mobile=$.trim($(form).find("#mobile").val());
			var rfor=$.trim($(form).find("#rfor").val());
			var rmsg=$.trim($(form).find("#rmsg").val());
			if(name.length==0 || email.length==0 || rfor.length==0 || rmsg.length==0) {
				alert("Please submit request content form properly.");
			} else {
				$.post("/request-for-content", {name:name,email:email,mobile:mobile,content_type:rfor,message:rmsg,action:'Submit'}).done(function(data) {
					alert("Your request successfully submitted.\nThank you requesting content from us.");
				});
			}
		};
		
		function generateSlug (value) {
		return value.toLowerCase().replace(/-+/g, '').replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
		}
		
		$.submitSearch=function(e) {
			var q=$(e).find("input[name='q']").val();
			q=generateSlug(q);
			var t=$(e).find("#searchOf").val();
			document.location="/"+t+"/"+q;
		};
		</script>
        <script src="/theme/js/custom.js"></script>
        <script src="/theme/js/switcher.js"></script>
		<?php
		include_once "./script.footer.php";
		?>
    </div>
</body>
</html>
