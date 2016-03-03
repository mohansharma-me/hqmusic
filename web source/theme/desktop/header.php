<div class="top-main-area text-center">
	<div class="container">
		<a href="index.html" class="logo mt5">
			<img src="/theme/logo_desktop.png" style="width:240px;margin-top:-25px;margin-bottom:-15px" alt="HQMusic.in" title="HQMusic.in" />
		</a>
	</div>
</div>
<header class="main">
	<div class="container">
		<div class="row">
			<div class="col-md-10">
				<!-- MAIN NAVIGATION -->
				<div class="flexnav-menu-button" id="flexnav-menu-button">Menu</div>
				<nav>
					<ul class="nav nav-pills flexnav" id="flexnav" data-breakpoint="800">
						<li class="active"><a href="/">Home</a></li>
						<?php
						$res=sql("select * from categories where category_parent_id=0");
						if(mysql_affected_rows()>0) {
							$count=0;
							while($row=mysql_fetch_assoc($res)) {
								//echo '<li><a href="/'.$row["category_slug"].'">'.$row["category_name"].'</a>';
								$res1=sql("select * from categories where category_parent_id=".$row["category_id"]);
								if(mysql_affected_rows()>0) {
									//echo "<ul>";
									while($row1=mysql_fetch_assoc($res1)) {
										$count++;
										if($count==7) {
											echo '<li><a href="javascript:void">More</a><ul>';
										}
										echo '<li><a href="/'.$row["category_slug"].'/'.$row1["category_slug"].'">'.$row1["category_name"].'</a></li>';
									}
									//echo "</ul>";
								}
								//echo '</li>';
							}
							if($count>=5) {
								echo "</ul></li>";
							}
						}
						?>
					</ul>
				</nav>
				<!-- END MAIN NAVIGATION -->
			</div>
			<div class="col-md-2">
				<ul class="login-register">
					<li><a class="popup-text" href="#request-content" data-effect="mfp-move-from-top"><i class="fa fa-edit"></i>Request Content</a>
				</ul>
			</div>
		</div>
	</div>
</header>
<div id="request-content" class="mfp-with-anim mfp-hide mfp-dialog clearfix">
	<i class="fa fa-sign-in dialog-icon"></i>
	<h3>Request Content</h3>
	<h5>submit your request content details...</h5>
	<form action="javascript:void" class="dialog-form" onSubmit="$.submitRC(this)">
		<div class="form-group">
			<label>Name</label>
			<input type="text" placeholder="your name" class="form-control" id="name">
		</div>
		<div class="form-group">
			<label>E-Mail</label>
			<input type="text" placeholder="mail@domain.com" class="form-control" id="email">
		</div>
		<div class="form-group">
			<label>Mobile</label>
			<input type="text" placeholder="mobile number" class="form-control" id="mobile">
		</div>
		<div class="form-group">
			<label>Request For:</label>
			<select class="form-control" id="rfor">
				<option value="album">Album</option>
				<option value="song">Song</option>
			</select>
		</div>
		<div class="form-group">
			<label>Request Message:</label>
			<textarea class="form-control" placeholder="your request message" id="rmsg"></textarea>
		</div>
		
		<input type="submit" value="Submit" class="btn btn-primary">
	</form>
</div>
<!-- SEARCH AREA -->
<form class="search-area form-group" action="javascript:void" onSubmit="$.submitSearch(this)">
	<div class="container">
		<div class="row">
			<div class="col-md-8 clearfix">
				<label><i class="fa">HQ</i><span>I am searching for</span>
				</label>
				<div class="search-area-division search-area-division-input">
					<input class="form-control" type="text" placeholder="Song, Album" value="<?=isset($_GET["q"])?str_replace("-"," ",$_GET["q"]):""?>" style='border-bottom:1px solid rgba(0,0,0,0.2)' name="q" />
				</div>
			</div>
			<div class="col-md-2">
				<select class="form-control" id="searchOf">
					<?php
					$selAlbum="";
					$selSong="";
					$selBoth="";
					$type=filterInput(INPUT_GET,"type");
					if(isset($type)) {
						if($type=="songs") {
							$selSong="selected";
						} else if($type=="albums") {
							$selAlbum="selected";
						} else {
							$selBoth="selected";
						}
					}
					?>
					<option value="albums" <?=$selAlbum?>>Albums</option>
					<option value="songs" <?=$selSong?>>Songs</option>
					<option value="both" <?=$selBoth?>>Albums + Songs</option>
				</select>
			</div>
			<div class="col-md-2">
				<button class="btn btn-block btn-white" type="submit">Search</button>
			</div>
		</div>
	</div>
</form>