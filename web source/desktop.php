<?php
$myTitle="HQMusic.in";
$myHead=null;
$contentPage="./contents/desktop/homepage.php";
$myData=array();
$ogTitle="HQMusic.in - High Quality Musics"; 
$ogImage="http://www.hqmusic.in/theme/qrcode.png"; 
$ogDesc="Download all your favorites music in high quality format...";
$meta=array();
$meta["keywords"]="";
$meta["description"]="";
if(count($keys)!=0) {
	$pageArray=array("dmca","privacy-policy","disclaimer","request-for-content","search1");
	$searchQ="";
	if(isset($_GET["q"])) {
		$searchQ=str_replace("-"," ",ucwords($_GET["q"]));
	}
	$pageTitle=array("dmca"=>"DMCA - HQMusic.in","privacy-policy"=>"Privacy Policy - HQMusic.in","disclaimer"=>"Disclaimer - HQMusic.in","request-for-content"=>"Request for content - HQMusic.in","search1"=>"$searchQ Search Result - HQMusic.in");
	$key1=strtolower(trim($keys[0]));
    if(in_array($key1,$pageArray)) {
		$pagefile="./contents/$key1.php";
		if(file_exists($pagefile) && is_file($pagefile)) {
			$myTitle=$pageTitle[$key1];
			$contentPage=$pagefile;
			$meta["keywords"]=$key1;
			$meta["description"]=$pageTitle[$key1];
		}
	} else {
		$isCategorySlug=false;
		$isSubCategorySlug=false;
		$query="";
		
		$first_slug=strtolower(trim($keys[0]));
		$query="select a.*,'super' as result from (select * from categories where category_parent_id=0) a where a.category_slug='$first_slug'";
		if(isset($keys[1])) {
			$isSubCategorySlug=true;
			$second_slug=strtolower(trim($keys[1]));
			$query="select a.*,'sub' as result,b.category_id as parent_id,b.category_name as parent_name,b.category_slug as parent_slug from (select * from categories where category_parent_id!=0) a, (select * from categories where category_parent_id=0) b where b.category_id=a.category_parent_id and b.category_slug='$first_slug' and a.category_slug='$second_slug'";
		}
		
		$res=sql($query);
		if(mysql_affected_rows()==1) { //yes category slug
			$isCategorySlug=true;
			$row=mysql_fetch_assoc($res);
			if($isSubCategorySlug) {
				$myTitle=$row["category_name"]." - ".$row["parent_name"]." - HQMusic.in";
				$ogTitle=$myTitle;
				$ogDesc="Download music from ".$row["category_name"].", ".$row["parent_name"];
				$contentPage="./contents/desktop/albums.php";
			} else {
				$myTitle=$row["category_name"]." - HQMusic.in";
				$ogTitle=$myTitle;
				$ogDesc="Download music from ".$row["category_name"];
				$contentPage="./contents/desktop/categories.php";
			}
			$meta["description"]=$row["category_name"].", high quality music collection - HQMusic.in";
			$meta["keywords"]=strtolower($myTitle).", ".str_replace("/"," ",str_replace("-"," ",$row["category_slug"])).", hqmusic";
			$myData["category"]=$row;
		}
		
		$isAlbum=false;
		if(!$isCategorySlug) {
			$isSubAlbumSlug=false;
			$query="select a.*,sub.category_slug as sub_slug,sub.category_name as sub_name,sub.category_id as sub_id,sup.category_slug as sup_slug,sup.category_name as sup_name,sup.category_id as sup_id from albums a, (select * from categories where category_parent_id!=0) sub, (select * from categories where category_parent_id=0) sup where lower(album_slug)='$first_slug' and sub.category_id=a.album_category_id and sup.category_id=sub.category_parent_id";
			if(isset($keys[1])) {
				$isSubAlbumSlug=true;
				$second_slug=strtolower(trim($keys[1]));
				$query="select f.*,s.*,a.*,sub.category_slug as sub_slug,sub.category_name as sub_name,sub.category_id as sub_id,sup.category_slug as sup_slug,sup.category_name as sup_name,sup.category_id as sup_id from files f,songs s, albums a, (select * from categories where category_parent_id!=0) sub, (select * from categories where category_parent_id=0) sup where lower(s.song_slug)='$second_slug' and lower(album_slug)='$first_slug' and f.file_song_id=s.song_id and song_album_id=album_id and sub.category_id=a.album_category_id and sup.category_id=sub.category_parent_id";
				if(isset($keys[2]) && is_numeric($keys[2])) {
					$kbps=$keys[2];
					$query="select f.*,s.*,a.*,sub.category_slug as sub_slug,sub.category_name as sub_name,sub.category_id as sub_id,sup.category_slug as sup_slug,sup.category_name as sup_name,sup.category_id as sup_id from files f,songs s, albums a, (select * from categories where category_parent_id!=0) sub, (select * from categories where category_parent_id=0) sup where lower(s.song_slug)='$second_slug' and lower(album_slug)='$first_slug' and f.file_song_id=s.song_id and f.kbps='$kbps' and song_album_id=album_id and sub.category_id=a.album_category_id and sup.category_id=sub.category_parent_id";
				}
			}
			$res=sql($query);
			if(mysql_affected_rows()>0) {
				$row=mysql_fetch_assoc($res);
				if($isSubAlbumSlug) {
					if(isset($keys[2]) && isset($keys[3])) {
						$kbps=trim($keys[2]);
						if(is_numeric($kbps)) {
							$hash=trim($keys[3]);
							$sign=$hash;//substr($hash,0,strlen($hash)-4);
							$songData=trim(strtolower($row["album_slug"]))."/".trim(strtolower($row["song_slug"]))."/$kbps/";
							$newSign=getIPHash($songData);
							if(strcmp($sign,$newSign)==0) {
								$res=sql("select s.song_name,a.album_name,sup.category_slug as sup,sub.category_slug as sub,a.album_slug as album,s.song_slug as song from categories sup, categories sub, albums a, songs s where lower(s.song_slug)='".strtolower(trim($row["song_slug"]))."' and a.album_id=s.song_album_id and sub.category_id=a.album_category_id and sup.category_id=sub.category_parent_id");
								if(is_resource($res)) {
									$r1=mysql_fetch_assoc($res);
									$sup=$r1["sup"];
									$sub=$r1["sub"];
									$album=$r1["album"];
									$song=$r1["song"];
									
									$song_name=ucwords(trim($r1["song_name"]));
									$album_name=ucwords(trim($r1["album_name"]));
									
									$realFile="./files/$sup/$sub/$album/$song/$kbps.mp3";
									$fakeFile="[HQMusic.in] $song_name - $album_name $kbps".""."Kbps.mp3";
									$sc_kbps=10;
									$sc_second=0;
									try {
										$sc=file_get_contents("./speed_control.json");
										$json=json_decode($sc,true);
										if(isset($json["kbps"]) && isset($json["second"])) {
											$sc_kbps=$json["kbps"];
											$sc_second=$json["second"];
										}
									} catch(Exception $e) {}
									giveDownload("$album_name|$song_name",$fakeFile, $realFile, "audio/mp3", $sc_kbps, $sc_second);
									exit;
								}
							}
						}
					}
				
					$myTitle=$row["song_name"]." - ".$row["album_name"]." - ".$row["sub_name"]." - ".$row["sup_name"]." - HQMusic.in";
					$contentPage="./contents/desktop/song.php";
					$meta["keywords"]=strtolower(str_replace(" - ",", ",$myTitle));
					$meta["description"]=$row["song_name"]." (".$row["album_name"].") ".$row["album_description"];
					$ogTitle=$myTitle;
					$ogDesc=$meta["description"];
					$ogImage="http://www.hqmusic.in/images/".$row["album_slug"]."_300_wwm.png";
					$myData["song"]=$row;
					$myData["songs"][]=$row;
					while($row=mysql_fetch_assoc($res)) {
						$myData["songs"][]=$row;
					}
				} else {
					$myTitle=$row["album_name"]." - ".$row["sub_name"]." - ".$row["sup_name"]." - HQMusic.in";
					$contentPage="./contents/desktop/songs.php";
					$myData["album"]=$row;
					$meta["keywords"]=strtolower(str_replace(" - ",", ",$myTitle));
					$meta["description"]="(".$row["album_name"].") ".$row["album_description"];
					$ogTitle=$myTitle;
					$ogDesc=$meta["description"];
					$ogImage="http://www.hqmusic.in/images/".$row["album_slug"]."_300_wwm.png";
				}
			}
		}
	}
}
if(count($keys)!=0 && $contentPage=="./contents/desktop/homepage.php") {
	header("Location: /search/".slug($key));
} else {
	include_once "./theme/desktop_template.php";
}