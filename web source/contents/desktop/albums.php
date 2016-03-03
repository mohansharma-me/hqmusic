<?php
$query="";
$parent_name=$myData["category"]["parent_name"];
$parent_slug=$myData["category"]["parent_slug"];
$category_name=$myData["category"]["category_name"];
$category_slug=$myData["category"]["category_slug"];
?>
<div class="gap"></div>
<div class="text-center">
	<h2 class="mb30"><?=$category_name?></h2>
		<?php
		$noi=24;
		if(isset($_GET["debug"])) {
			$noi=999999;
		}
		$page=1;
		if(isset($_GET["page"]) && is_numeric($_GET["page"])) {
			$page=$_GET["page"];
		}
		$start=($page-1)*$noi;
		$res=sql("select SQL_CALC_FOUND_ROWS album_id,a.* from albums a where album_category_id=".$myData["category"]["category_id"]." order by album_art, album_year desc limit $start,$noi");
		if(mysql_affected_rows()>0) {
			$res1=sql("select found_rows() as total_rows");
			$totalRows=mysql_fetch_assoc($res1)["total_rows"];
			$colCount=0;
			while($row=mysql_fetch_assoc($res)) {
			if($colCount==0) {
				echo '<div class="row">';
			}
			$altName=$row["album_name"];
			$album_name=$row["album_name"];
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
		<a class="col-md-2 col-masonry" href="/<?=$album_slug?>">
			<div class="product-thumb">
				<header class="product-header">
					<img src="<?=$img_path?>" alt="<?=$altName?>" title="<?=$altName?>" style="height:195px"/>
				</header>
				<div class="product-inner">
					<h5 class="product-title"><?=$album_name?></h5>
				</div>
			</div>
		</a>
		<?php
			if($colCount==5) {
				echo '</div><div class="gap"></div>';
				$colCount=-1;
			}
			$colCount++;
			}
			if($colCount!=0) {
				echo '</div><div class="gap"></div>';
			}
			echo "<div class='row text-center'>";
			$mainLink="/$parent_slug/$category_slug/";
			$totalPages=ceil($totalRows/$noi);
			echo "</div>";
			/*if($page>1) {
				echo "<a href='$prvLink' class='btn btn-primary'>PREVIOUS</a> ";
			}
			echo "<a class='btn'>Page $page of $totalPages</a> ";
			if($page<$totalPages) {
				echo "<a href='$nxtLink' class='btn btn-primary'>NEXT</a>";
			}*/
			
			$start=$page-4;
			if($start<1) {
				$start=1;
			}
			$end=$start+9;
			if($start>=2) {
				echo '<a href="?page=1" class="btn">1</a> ... ';
			}
			$lastPage=$totalPages;
			for($i=$start;$i<$end && $i<=$lastPage;$i++) {
				$link="?page=$i";
				$btnP="";
				if($i==$page) {
					$btnP=" btn-primary";
				}
				echo '<a href="'.$link.'" class="btn'.$btnP.'">'.$i.'</a> ';
			}
			if($page+4<$lastPage)
			echo ' ... <a href="?page='.$lastPage.'" class="btn">'.$lastPage.'</a>';
			
			echo "<div>";
			echo "</div>";
		} else {
		?>
		<b><label>No albums.</label></b>
		<?php
		}
		?>
	<br/>
</div>
<div class="gap"></div>