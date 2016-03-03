<?php
$query="";
$sup_name=$myData["song"]["sup_name"];
$sup_slug=$myData["song"]["sup_slug"];
$sub_name=$myData["song"]["sub_name"];
$sub_slug=$myData["song"]["sub_slug"];
$album_name=$myData["song"]["album_name"];
$album_slug=$myData["song"]["album_slug"];
$album_description=$myData["song"]["album_description"];
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>  

<div class="gap"></div>
<div class="row">
	<div class="col-md-3">
		<?php
		$res=sql("select sup.category_slug as sup_slug,sub.category_slug as sub_slug, sup.category_id as sup,sub.category_id as sub,sup.category_name as sup_name, sub.category_name as sub_name from (select * from categories where category_parent_id=0) sup, (select * from categories where category_parent_id!=0) sub where sub.category_id=".$myData["song"]["album_category_id"]." and sup.category_id=sub.category_parent_id");
		if(mysql_affected_rows()>0) {
			$row=mysql_fetch_assoc($res);
			$sup_name=$row["sup_name"];
			$sub_name=$row["sub_name"];
			$sup_slug=$row["sup_slug"];
			$sub_slug=$row["sub_slug"];
			$sup_id=$row["sup"];
			$sub_id=$row["sub"];
		?>
		<aside class="sidebar-left hidden-phone">
			<div class="sidebar-box">				
				<?php
					echo '<img src="/images/'.$album_slug.'_300_wwm.png" />';
				?><br/><br/>
				<h5 align=center><?=$album_name?></h5>
			</div>
			
			<?php
			$res=sql("select * from categories left join (select * from albums order by album_art,album_year, rand() desc) a on album_category_id=category_id where category_parent_id=$sup_id group by category_id order by rand() limit 5");
			if(mysql_affected_rows()>0) {
			?>
			<div class="sidebar-box">
				<h5><?=$sup_name?></h5>
				<table class="table table-order">	
					<?php
					while($row=mysql_fetch_assoc($res)) {
						$name=$row["category_name"];
						$slug=$row["category_slug"];
						$img_path="/theme/default_category.png";
						if(isset($row["album_slug"])) {
							$img_path="/images/".$row["album_slug"]."_thumb.png";
						}
						$link="/$sup_slug/$slug";
					?>
					<tr>
						<td class="table-order-img"><img src="<?=$img_path?>" alt="<?=$name?>" title="<?=$name?>" style="height:64px;" /></td>
						<td>	
							<a href="<?=$link?>">
								<h5 class="thumb-list-item-title"><a href="<?=$link?>"><?=$name?></a></h5>
							</a>
						</td>
					</tr>
					<?php
					}
					?>
				</table>
			</div>
			<?php
			}
			?>
			<?php
			$res=sql("select * from albums where album_category_id=$sub_id order by album_art, rand() desc limit 6");
			if(mysql_affected_rows()>0) {
			?>
			<div class="sidebar-box">
				<h5><?=$sub_name?></h5>
				<table class="table table-order">	
					<?php
					while($row=mysql_fetch_assoc($res)) {
						$name=$row["album_name"];
						$slug=$row["album_slug"];
						$img_path="/images/$slug"."_thumb.png";
						$link="/$slug";
					?>
					<tr>
						<td class="table-order-img">
						<a href="<?=$link?>">
							<img src="<?=$img_path?>" alt="<?=$name?>" title="<?=$name?>" style="height:64px" />
						</a>
						</td>
						<td>
							<h5 class="thumb-list-item-title"><a href="<?=$link?>"><?=$name?></a></h5>
						</td>
					</tr>
					<?php
					}
					?>
				</table>
			</div>
			<?php
			}
			?>
		</aside>
		<?php
		}
		?>
	</div>
	
	<div class="col-md-9">
		<div class="row">
			<article class="post">
				<header class="post-inner post-header dwnl-post">
					<?php $downloadSong=true; ?>
					<div class="tab-pane fade in active" id="tab-downloader">
					
					<h2 style="text-align:center"><?=$myData["song"]["song_name"]?> - <?=$myData["song"]["kbps"]?>Kbps (<?=$myData["song"]["file_size"]?>)</h2>
					<br/>
					<center style="padding:10px;background:#ddd">
						Please wait<br/><br/><h1>10s</h1> to download
						<br/>
					</center>
					</div>
				</header>
				<div class="post-inner">
					<h4 class="post-title"><a href=""><?=$album_name?></a></h4>
					<ul class="post-meta">
						<li><i class="fa fa-check"></i>Year: <?=$myData["song"]["album_year"]?></li>
						<li><i class="fa fa-check"></i>Cast: <?=$myData["song"]["album_cast"]?></li>
						<?php
						try {
						$json=json_decode($myData["song"]["album_imdb_json"],true);
							if(is_array($json) && count($json)>0) {
								echo '<li><i class="fa fa-check"></i>Released: '.ucwords($json["released"]).'</li>';
								echo '<li><i class="fa fa-check"></i>Genre: '.ucwords($json["genre"]).'</li>';
								echo '<li><i class="fa fa-check"></i>Director: '.ucwords($json["director"]).'</li>';
								echo '<li><i class="fa fa-check"></i>Writer: '.ucwords($json["writer"]).'</li>';
							}
						} catch(Exception $e) {}
						?>
					</ul>
					<br/>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Song Title</th>
								<th>Kbps</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$res=sql("select s.*,group_concat(f.kbps separator ' ') as kbps from files f, songs s where file_song_id=song_id and song_album_id=".$myData["song"]["album_id"]." group by song_slug");
						if(mysql_affected_rows()>0) {
							while($row=mysql_fetch_assoc($res)) {
								if(strlen(trim($album_name))>31) {
									//$album_name=substr($album_name,0,30)."...";
								}
								$song_name=$row["song_name"];
								$song_slug=$row["song_slug"];
								$link="/$album_slug/$song_slug";
								$img_path="/images/$album_slug"."_300_wwm.png";
								?>
								<tr>
									<td align=left><a href='<?=$link?>'><?=$song_name?></a></td>
									<td align=left>
										<?php
											$kbs=explode(' ',$row["kbps"]);
											foreach($kbs as $kb) {
												if(strlen(trim($kb))>0) {
													echo "[ <a href='/".$myData["song"]["album_slug"]."/".$row["song_slug"]."/$kb/'>$kb"."Kbps</a> ] ";
												}
											}
											$link="http://hqmusic.in/".$myData["album"]["album_slug"]."/".$row["song_slug"]."/".$kbs[count($kbs)-1]."/";
											echo '<div class="fb-like" style="position:absolute" data-href="'.$link.'" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>';
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>
						</tbody>
					</table>
					<br/>
					<p class="post-desciption">
						<?=$album_description?>
					</p>
					<p class="post-desciption">
						<span class='st_facebook_large' displayText='Facebook'></span>
						<span class='st_twitter_large' displayText='Tweet'></span>
						<span class='st_email_large' displayText='Email'></span>
					</p>
				</div>
			</article>
		</div>
		<div class="gap"></div>
		
		<?php
		$curYear=date("Y",strtotime("now"));
		$res=sql("select * from albums where (album_year='$curYear' or album_year='".($curYear-1)."' or album_year='".($curYear-1)."') and album_id!=".$myData["song"]["album_id"]." and album_category_id=".$myData["song"]["album_category_id"]." order by album_art, album_year, rand() desc limit 4");
		if(mysql_affected_rows()>0) {
		?>
		<h3>Related Albums</h3>
		<div class="gap gap-mini"></div>
		<div class="row row-wrap">
			<?php
			while($row=mysql_fetch_assoc($res)) {
				$name=$row["album_name"];
				$altName=$name;
				$len=strlen(trim($name));
				if($len>31) {
					$name=substr($name,0,30)."...";
				} else {
					if($len==15) {$name.="&nbsp;";$len++;}
					if($len<=16)
					$name.="<br/><br/>";
				}
				$slug=$row["album_slug"];
				$img_path="/images/$slug"."_300_wwm.png";
				$link="/$slug";
			?>
			<div class="col-md-3">
				<a href="<?=$link?>">
				<div class="product-thumb">
					<header class="product-header">
						<img src="<?=$img_path?>" alt="<?=$altName?>" title="<?=$altName?>" style="height:195px" />
					</header>
					<div class="product-inner">
						<h5 class="product-title"><?=$name?></h5>
					</div>
				</div>
				</a>
			</div>
			<?php
			}
			?>
		</div>
		<div class="gap gap-small"></div>
		<?php
		}
		?>
	</div>
</div>