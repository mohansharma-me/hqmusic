<div class="text-center">
	<?php
	$res=sql("select * from albums order by rand() limit 6");
	if(mysql_affected_rows()>0) {
	?>
	<h2 class="mb30" style="margin-top:-25px">Most Favorite Albums</h2>
	<div class="row row-wrap" id="masonry">
		<?php
		while($row=mysql_fetch_assoc($res)) {
		$album_name=$row["album_name"];
		if(strlen(trim($album_name))>31) {
			$album_name=substr($album_name,0,30)."...";
		} else {
					$album_name.="<br/><br/>";
		}
		$album_slug=$row["album_slug"];
		$link="/$album_slug";
		$img_path="/images/$album_slug"."_300_wwm.png";
		?>
		<a class="col-md-2 col-masonry" href="<?=$link?>">
			<div class="product-thumb">
				<header class="product-header">
					<img src="<?=$img_path?>" alt="<?=$album_name?>" title="<?=$album_name?>" />
				</header>
				<div class="product-inner">
					<h5 class="product-title"><?=$album_name?></h5>
				</div>
			</div>
		</a>
		<?php
		}
		?>
	</div>
	<?php
	}
	?>
</div>
<div class="gap"></div>