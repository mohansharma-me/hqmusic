<?php
$albums=array();
$songs=array();
$album_search_count=0;
$song_search_count=0;
$error="";

$album_page=1;
$noAlbums=12;
$totalAlbums=0;

$song_page=1;
$noSongs=15;
$totalSongs=0;


$q=filterInput(INPUT_GET,"q");
$type=filterInput(INPUT_GET,"type");
$sSong=false;
$sAlbum=false;
$sBoth=false;
if(isset($q) && isset($type)) {	
	if($type=="songs") $sSong=true;
	if($type=="albums") $sAlbum=true;
	if($type=="both") $sBoth=true;
	$album_search_count=$song_search_count=0;
	
	$q=str_replace("-"," ",$q);
	$album_page=filterInput(INPUT_GET,"album_page");
	if(!isset($album_page)) {
		$album_page=1;
	}
	$album_start=($album_page-1)*$noAlbums;
	
	$qq=array();
	
	//$albumLike="(1!=1 or";
	$albumLike="";
	$songLike="";
	$qs=explode("|",$q);
	$nq="";
	foreach($qs as $q1) {
		//$qq[]=$q1;
		$qs1=explode(" ",$q1);
		foreach($qs1 as $q2) {
			if(strlen(trim($q2))>0) {
				$nq.="$q2 ";
				$albumLike="(select * from albums where lower(album_name) LIKE '%".trim($nq)."%') union ".$albumLike;
				$songLike="(select * from songs, albums where album_id=song_album_id and lower(song_name) LIKE '%".trim($nq)."%') union ".$songLike;
				$qq[]=$nq;
				//$albumLike.=" lower(album_name) LIKE '%$q2%' or";
				//$songLike.=" lower(song_name) LIKE '%$q2%' or";
			}
		}
	}
	
	//$albumLike.=" 1!=1)";
	//$songLike.=" 1!=1)";

	$albumLike=substr($albumLike,0,strlen($albumLike)-6);
	$songLike=substr($songLike,0,strlen($songLike)-6);
	
	//echo $albumLike."<br/>";
	/*echo "select SQL_CALC_FOUND_ROWS album_id,albums.* from ($albumLike) albums<br/>";
	echo "<pre>";
	print_r($qq);
	echo "</pre>";
	
	echo $songLike."<br/>";
	echo "select SQL_CALC_FOUND_ROWS song_id,a.* from ($songLike) a<br/>";
	*/
	
	//name,desc,cast,json,year,
	if($sAlbum || $sBoth) {
		//$res_a=sql("select SQL_CALC_FOUND_ROWS album_id,albums.* from albums where $albumLike limit $album_start,$noAlbums");
		$res_a=sql("select SQL_CALC_FOUND_ROWS album_id,albums.* from ($albumLike) albums limit $album_start,$noAlbums");
		$album_search_count=mysql_affected_rows();
		if($album_search_count>0) {
			$res1=sql("select found_rows() as total_rows");
			$row=mysql_fetch_assoc($res1);
			$totalAlbums=$row["total_rows"];
			while($row=mysql_fetch_assoc($res_a)) {
				$albums[]=$row;
			}
		}
	}
	
	if($sSong || $sBoth) {
		$song_page=filterInput(INPUT_GET,"song_page");
		if(!isset($song_page)) {
			$song_page=1;
		}
		$song_start=($song_page-1)*$noSongs;
		
		//$res_s=sql("select  SQL_CALC_FOUND_ROWS song_id,songs.*,albums.* from songs, albums where album_id=song_album_id and $songLike limit $song_start,$noSongs");
		$res_s=sql("select  SQL_CALC_FOUND_ROWS song_id,a.* from ($songLike) a limit $song_start,$noSongs");
		$song_search_count=mysql_affected_rows();
		if($song_search_count>0) {
			$res1=sql("select found_rows() as total_rows");
			$row=mysql_fetch_assoc($res1);
			$totalSongs=$row["total_rows"];
			while($row=mysql_fetch_assoc($res_s)) {
				$songs[]=$row;
			}
		}
	}
	if(strlen(trim($q))==0) {
		$album_search_count=$song_search_count=0;		
	}
} else {
	$error="Please enter valid search terms...";
}
?>

<?php
if(_MOBILE_) {

if($album_search_count==0 && $song_search_count==0) {
	echo '<div class="content-holder">
    <div class="content">';
	echo "<ul class='style-list1'>";
	echo "<h1>No results found.</h1>";
	echo "</ul>";
	echo '</div></div>';
}

if($song_search_count!=0 && ($sSong || $sBoth)) {
	echo '<div class="content-holder">
		  <div class="content">';
	echo "<ul class='style-list1'>";
	echo "<h1>$totalSongs song(s) found.</h1>";
	foreach($songs as $row) {
		$slug=$row["album_slug"];
		$song_slug=$row["song_slug"];
		$name=$row["song_name"];
		echo "<li><label><a href='/$slug/$song_slug'><img src='/images/$slug"."_thumb.png' /> $name</a></label></li>";
	}
	if($sBoth) {
		$curAlbum="&album_page=$album_page";
	} else {
		$curAlbum="";
	}
	$totalPages=ceil($totalSongs/$noSongs);
	/*if($song_page>1) {
		$nP=$song_page-1;
		echo '<a href="?q='.$q.'&song_page='.$nP.$curAlbum.'">Previous</a> ... ';
	}
	if($totalPages>=$song_page+1) {
		$nP=$song_page+1;
		echo '<a href="?q='.$q.'&song_page='.$nP.$curAlbum.'">Next</a>';
	}*/

//	$curAlbum="&album_page=$album_page";
	$start=$song_page-4;
	if($start<1) {
		$start=1;
	}
	$end=$start+9;
	if($start>=2) {
		//echo '<a href="?q='.$q.'&song_page=1'.$curAlbum.'" class="btn">1</a> ... ';
		echo '<a href="?song_page=1'.$curAlbum.'" class="btn">1</a> ... ';
	}
	$lastPage=ceil($totalSongs/$noSongs);
	for($i=$start;$i<$end && $i<=$lastPage;$i++) {
		$link="?song_page=$i$curAlbum";
		$btnP="";
		if($i==$song_page) {
			$btnP=" btn-primary";
		}
		echo '<a href="'.$link.'" class="btn'.$btnP.'">'.$i.'</a> ';
	}
	if($song_page+4<$lastPage)
	echo ' ... <a href="?song_page='.$lastPage.$curAlbum.'" class="btn">'.$lastPage.'</a>';
	
	echo "</ul>";
	echo '</div></div>';
}	
if($album_search_count!=0 && ($sAlbum || $sBoth)) {
	echo '<div class="content-holder">
    <div class="content">';
	echo "<ul class='style-list1'>";
	echo "<h1>$totalAlbums album(s) found.</h1>";
	foreach($albums as $row) {
		$slug=$row["album_slug"];
		$name=$row["album_name"];
		echo "<li><label><a href='/$slug'><img src='/images/$slug"."_thumb.png' /> $name</a></label></li>";
	}
	//$curSong="&song_page=$song_page";
	if($sBoth) {
		$curSong="&song_page=$song_page";
	} else {
		$curSong="";
	}
	$totalPages=ceil($totalAlbums/$noAlbums);
	/*if($album_page>1) {
		$nP=$album_page-1;
		echo '<a href="?q='.$q.'&album_page='.$nP.$curSong.'">Previous</a> ... ';
	}
	if($totalPages>=$album_page+1) {
		$nP=$album_page+1;
		echo '<a href="?q='.$q.'&album_page='.$nP.$curSong.'">Next</a>';
	}*/
	
//	$curSong="&song_page=$song_page";
	$start=$album_page-4;
	if($start<1) {
		$start=1;
	}
	$end=$start+9;
	if($start>=2) {
		echo '<a href="?album_page=1'.$curSong.'" class="btn">1</a> ... ';
	}
	$lastPage=ceil($totalAlbums/$noAlbums);
	for($i=$start;$i<$end && $i<=$lastPage;$i++) {
		$link="?album_page=$i$curSong";
		$btnP="";
		if($i==$album_page) {
			$btnP=" btn-primary";
		}
		echo '<a href="'.$link.'" class="btn'.$btnP.'">'.$i.'</a> ';
	}
	if($album_page+4<$lastPage)
	echo ' ... <a href="?album_page='.$lastPage.$curSong.'" class="btn">'.$lastPage.'</a>';
	
	echo "</ul>";
	echo '</div></div>';
}
} else {

if($album_search_count==0 && $song_search_count==0) {
?>
<br/><br/><br/>
<div class="text-center">
	<h2 class="mb30" style="margin-top:-25px">No results found.</h2>
</div>
<?php
}

if($song_search_count!=0 && ($sSong || $sBoth)) {
?>
<br/><br/><br/>
<div class="text-center">
	<h2 class="mb30" style="margin-top:-25px"><?=$totalSongs?> song(s) found.</h2>
	<table class="table cart-table">
		<thead>
			<tr>
				<th>Song Title</th>
				<th>Album Name</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach($songs as $row) {
		$album_name=$row["song_name"];
		if(strlen(trim($album_name))>31) {
			//$album_name=substr($album_name,0,30)."...";
		}
		$albumname=$row["album_name"];
		$album_slug=$row["album_slug"];
		$song_slug=$row["song_slug"];
		$link="/$album_slug/$song_slug";
		$img_path="/images/$album_slug"."_300_wwm.png";
		?>
		<tr>
			<td align=left><a href='<?=$link?>'><?=$album_name?></a></td>
			<td align=left><a href='/<?=$album_slug?>'><?=$albumname?></a></td>
		</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<div class="row">
		<?php
		if($sBoth) {
			$curAlbum="&album_page=$album_page";
		} else {
			$curAlbum="";
		}
		$start=$song_page-4;
		if($start<1) {
			$start=1;
		}
		$end=$start+9;
		if($start>=2) {
			echo '<a href="?song_page=1'.$curAlbum.'" class="btn">1</a> ... ';
		}
		$lastPage=ceil($totalSongs/$noSongs);
		for($i=$start;$i<$end && $i<=$lastPage;$i++) {
			$link="?song_page=$i$curAlbum";
			$btnP="";
			if($i==$song_page) {
				$btnP=" btn-primary";
			}
			echo '<a href="'.$link.'" class="btn'.$btnP.'">'.$i.'</a> ';
		}
		if($song_page+4<$lastPage)
		echo ' ... <a href="?song_page='.$lastPage.$curAlbum.'" class="btn">'.$lastPage.'</a>';
		?>
	</div>
</div>
<div class="gap"></div>
<?php
}
if($album_search_count!=0 && ($sAlbum || $sBoth)) {
?>
<br/><br/><br/>
<div class="text-center">
	<h2 class="mb30" style="margin-top:-25px"><?=$totalAlbums?> album(s) found.</h2>
	<div class="row row-wrap" id="masonry">		
		<?php
		$count=0;
		foreach($albums as $row) {
		
		if($count==0) {
			echo '<div class="row">';
		}
		
		$album_name=$row["album_name"];
		$altName=$album_name;
		$len=strlen(trim($album_name));
		if($len>31) {
			$album_name=substr($album_name,0,30)."...";
		} else {
			if($len==15) {$album_name.="&nbsp;";$len++;}
			if($len<=16)
			$album_name.="<br/><br/>";
		}
		$album_slug=$row["album_slug"];
		$link="/$album_slug";
		$img_path="/images/$album_slug"."_300_wwm.png";
		?>
		<a class="col-md-2 col-masonry" href="<?=$link?>">
			<div class="product-thumb">
				<header class="product-header">
					<img src="<?=$img_path?>" alt="<?=$altName?>" title="<?=$altName?>" style="height:195px" />
				</header>
				<div class="product-inner">
					<h5 class="product-title"><?=$album_name?></h5>
				</div>
			</div>
		</a>
		<?php
		if($count==5) {
			echo '</div><div class="gap"></div>';
			$count=-1;
		}
		$count++;
		}
		if($count!=0) {
			echo '</div><div class="gap"></div>';
		}
		
		?>
	</div>
	<div class="row">
		<?php
		//$curSong="&song_page=$song_page";
		if($sBoth) {
			$curSong="&song_page=$song_page";
		} else {
			$curSong="";
		}
		$start=$album_page-4;
		if($start<1) {
			$start=1;
		}
		$end=$start+9;
		if($start>=2) {
			echo '<a href="?album_page=1'.$curSong.'" class="btn">1</a> ... ';
		}
		$lastPage=ceil($totalAlbums/$noAlbums);
		for($i=$start;$i<$end && $i<=$lastPage;$i++) {
			$link="?album_page=$i$curSong";
			$btnP="";
			if($i==$album_page) {
				$btnP=" btn-primary";
			}
			echo '<a href="'.$link.'" class="btn'.$btnP.'">'.$i.'</a> ';
		}
		if($album_page+4<$lastPage)
		echo ' ... <a href="?album_page='.$lastPage.$curSong.'" class="btn">'.$lastPage.'</a>';
		?>
	</div>
</div>
<div class="gap"></div>
<?php
}

}
?>