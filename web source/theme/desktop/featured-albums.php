<div class="bg-holder" style="opad=">
	<div class="bg-mask"></div>
	<div class="bg-blur"></div>
	<div class="container bg-holder-content">
		<?php
		$res=sql("select * from (select * from updates order by update_date desc) updates, albums where update_album_id=album_id order by album_art, rand() desc limit 6");
		if(mysql_affected_rows()>0) {
		?>
		<div class="text-center" style="margin-top:10px">
			
			<div class="row row-wrap">
				<?php
				while($row=mysql_fetch_assoc($res)) {
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
				<a class="col-md-2" href="<?=$link?>" style="margin-bottom:10px">
					<div class="product-thumb">
						<header class="product-header">
							<img src="<?=$img_path?>" alt="<?=$altName?>" title="<?=$altName?>" style="height:195px" />
						</header>
						<div class="product-inner">
							<h5 class="product-title" style=""><?=$album_name?></h5>
						</div>
					</div>
				</a>
				<?php
				}
				?>
			</div>	
		</div>
		<?php
		}
		?>
	</div>
</div>
<div style="margin-top:10px"></div>
