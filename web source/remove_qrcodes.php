<?php
$qrcode_img_size=filesize("./theme/qrcode.png");
include_once "./inc/inc.functions.php";
$res=sql("select * from albums");
if(mysql_affected_rows()>0) {
	while($row=mysql_fetch_assoc($res)) {
		$id=$row["album_id"];
		$slug=$row["album_slug"];
		$img_path="./images/$slug"."_original";
		if(file_exists($img_path)) {
			$imgSize=filesize($img_path);
			if($imgSize==$qrcode_img_size) {
				sql("update albums set album_art='false' where album_id=$id");
				try {
				unlink($img_path);
				unlink("./images/$slug"."_300");
				unlink("./images/$slug"."_300_wwm");
				unlink("./images/$slug"."_thumb");
				} catch(Exception $e) {}
			}
		} else {
			sql("update albums set album_art='false' where album_id=$id");
		}
	}
}