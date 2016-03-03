<?php
$query="";
$category_name=$myData["category"]["category_name"];
$category_slug=$myData["category"]["category_slug"];
?>
</div>
<div class="bg-holder">
	<div class="bg-mask"></div>
	<div class="bg-blur"></div>
	<div class="container bg-holder-content">
		<div class="gap gap-big text-center">
			<h1 class="mb30 text-white"><?=$category_name?></h1>
			<div class="row row-wrap">
				<?php
				$res=sql("select * from categories left join (select * from albums where album_art!='false' order by rand()) a on album_category_id=category_id where category_parent_id=".$myData["category"]["category_id"]." group by category_id");
				if(mysql_affected_rows()>0) {
					while($row=mysql_fetch_assoc($res)) {
						$img_path="/theme/default_category.png";
						if(isset($row["album_slug"])) {
							$album_slug=$row["album_slug"];
							$img_path="/images/$album_slug"."_300_wwm.png";
						}
						$name=$row["category_name"];
						$len=strlen(trim($name));
						if($len>31) {
							$name=substr($name,0,30)."...";
						} else {
							if($len==15) {$name.="&nbsp;";$len++;}
							if($len<=16)
							$name.="<br/><br/>";
						}
						?>
						<a class="col-md-3" href="/<?=$category_slug?>/<?=$row["category_slug"]?>">
							<div class="product-thumb">
								<header class="product-header">
									<img src="<?=$img_path?>" alt="<?=$row["category_name"]?>" title="<?=$row["category_name"]?>" style="height:450px"/>
								</header>
								<div class="product-inner">
									<h5 class="product-title"><?=$name?></h5>
								</div>
							</div>
						</a>
						<?php
					}
				} else {
					echo '<label class="text-white">No sub-categories.</label><Br/><br/>';
				}
				?>
			</div>	
			
		</div>
	</div>
</div>