<?php
echo "ASD";
exit(0);
set_time_limit(0);
$imgs=scandir("./images");
foreach($imgs as $_img) {
	if(strpos($_img,"_original")!=false) {
		$slug=str_replace("_original","",$_img);
		$_img="./images/".$_img;
		$jpeg=false;
		$png=false;
		$img=null;

		$img=imagecreatefrompng($_img);
						
		$ori_size=600;
						
		list($wid,$hei)= getimagesize($_img);
		$o_w=$wid;
		$o_h=$hei;
		if($wid>$hei) {
			$hei=($hei*$ori_size)/$wid;
			$wid=$ori_size;
		} else {
			$wid=($wid*$ori_size)/$hei;
			$hei=$ori_size;
		}
		$thumb=imagecreatetruecolor($wid, $hei);
		
		imagecopyresized($thumb, $img, 0, 0, 0, 0, $wid, $hei, $o_w, $o_h);
		imagepng($thumb, "./images/$slug"."_300_wwm",9);
		imagedestroy($thumb);
	}
}