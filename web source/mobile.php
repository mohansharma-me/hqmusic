<?php
$myTitle=null;
$myHead=null;
$contentPage="./contents/mobile/homepage.php";
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
		$searchQ=ucwords($_GET["q"]);
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
		$findAlbumFlag=false;
		$isSubCategory=false;
		$query="select * from categories where lower(category_slug)='$key1'";
		if(isset($keys[1])) {
			$key2=strtolower(trim($keys[1]));
			$query="select a.*, b.category_id as p_category_id,b.category_name as p_category_name,b.category_parent_id as p_category_parent_id,b.category_slug as p_category_slug from (select * from categories where category_parent_id!=0) a, (select * from categories where category_parent_id=0) b where lower(a.category_slug)='$key2' and lower(b.category_slug)='$key1' and b.category_id=a.category_parent_id";
			$isSubCategory=true;
		}
		$res=sql($query);
		if(is_resource($res)) { // yes category slug
			if(mysql_affected_rows()==1) {
				$row=mysql_fetch_assoc($res);
				if($isSubCategory) {
					$myTitle=ucwords($row["category_name"])." - ".ucwords($row["p_category_name"]);
					$ogTitle=$myTitle;
					$ogDesc="Download music from ".$row["category_name"].", ".$row["parent_name"];
					$contentPage="./contents/mobile/albums.php";
				} else {
					$myTitle=ucwords($row["category_name"]);
					$ogTitle=$myTitle;
					$ogDesc="Download music from ".$row["category_name"];
					$contentPage="./contents/mobile/categories.php";
				}
				$meta["description"]=$row["category_name"].", high quality music collection - HQMusic.in";
				$meta["keywords"]=strtolower($myTitle).", ".str_replace("/"," ",str_replace("-"," ",$row["category_slug"])).", hqmusic";
				$myData=$row;
			} else { // invalid link or may be injection

			}
		} else if(is_numeric($res) && $res==0) { // not category slug
			$findAlbumFlag=true;
		} else if(is_numeric($res) && $res==-1) { // invalid link or may be injection
			$findAlbumFlag=false;
		}
		
		if($findAlbumFlag) {
			$isSongs=false;
			$key1=strtolower(trim($keys[0]));
			$query="select * from albums a, categories c where lower(album_slug)='$key1' and c.category_id=a.album_category_id";
			if(isset($keys[1])) {
				$key2=strtolower(trim($keys[1]));
				$query="select * from albums a, songs s where s.song_album_id=a.album_id and lower(a.album_slug)='$key1' and lower(s.song_slug)='$key2'";
				$isSongs=true;
			}
			$res=sql($query);
			if(is_resource($res)) { // yes album/song slug
				if(mysql_affected_rows()==1) {
					$row=mysql_fetch_assoc($res);
					if($isSongs) {
						$flag=true;
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
						if($flag) {
							$myTitle=ucwords($row["song_name"])." - ".ucwords($row["album_name"]);
							$meta["keywords"]=strtolower(str_replace(" - ",", ",$myTitle));
							$meta["description"]=$row["song_name"]." (".$row["album_name"].") ".$row["album_description"];
							$ogTitle=$myTitle;
							$ogDesc=$meta["description"];
							$ogImage="http://www.hqmusic.in/images/".$row["album_slug"]."_300_wwm.png";
							
							$contentPage="./contents/mobile/song.php";
						}
					} else {
						$myTitle=ucwords($row["album_name"])." - ".ucwords($row["category_name"]);
						$contentPage="./contents/mobile/songs.php";
						$meta["keywords"]=strtolower(str_replace(" - ",", ",$myTitle));
						$meta["description"]="(".$row["album_name"].") ".$row["album_description"];
						$ogTitle=$myTitle;
						$ogDesc=$meta["description"];
						$ogImage="http://www.hqmusic.in/images/".$row["album_slug"]."_300_wwm.png";
					}
					$myData=$row;
				} else { // invalid link or may be injection

				}
			} else if(is_numeric($res)==0) { // not album/song slug
				$findAlbumFlag=true;
			} else if(is_numeric($res)==-1) { // invalid link or may be injection
				$findAlbumFlag=false;
			}
		}
	}
}

if(count($keys)!=0 && $contentPage=="./contents/mobile/homepage.php") {
	//header("Location: /search/?q=".str_replace("-"," ",str_replace("/"," ",$key)));
	header("Location: /search/".slug($key));
} else {
	include_once "./theme/mobile_template.php";
}