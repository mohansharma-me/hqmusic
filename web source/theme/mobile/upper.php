<!doctype html>
<html>
    <head>
        <?php
        if(isset($myTitle)) {
        ?>
        <title><?=$myTitle?> - HQMusic.in</title>
        <?php
        } else {
        ?>
        <title>HQ Music</title>
        <?php
        }
	$metaKeywords="hqmusic, high quality songs";
	$metaDescs="HQMusic.in Collection of High Quality Songs";
	if(isset($meta["keywords"]) && strlen($meta["keywords"])>0) {
		$metaKeywords=$meta["keywords"];
	}
	if(isset($meta["description"]) && strlen($meta["description"])>0) {
		$metaDescs=$meta["description"];
	}
	
	?>
	
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="keywords" content="<?=$metaKeywords?>" />
    <meta name="description" content="<?=$metaDescs?>" />
    <meta property="og:title" content="<?=$ogTitle?>" />
    <meta property="og:image" content="<?=$ogImage?>" />
    <meta property="og:description" content="<?=$ogDesc?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="favicon" href="/theme/favicon.ico" />
        <link rel="stylesheet" href="/theme/mobile.css" media="screen" />
        <?php
        if(isset($myHead)) {
            echo $myHead;
        }
		include_once "./script.header.php";
        ?>
	<script type="text/javascript">
	function generateSlug (value) {
	return value.toLowerCase().replace(/-+/g, '').replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
	}
	function submitSearch(e) {
		var x=document.getElementById("q");
		var t=document.getElementById("searchOf").value;
		document.location="/"+t+"/"+generateSlug(q.value);
	}
	</script>
    </head>
    <body>
        <div class="page">
            <div class="header">
                <div class="logo">
                    <a href="/"><label><img src="/theme/logo.png" style="width:200px" /></label></a>
                </div>
                <div class="search">
                    <form method="get" action="javascript:void" id="searchForm" onSubmit="submitSearch(this)">
				<fieldset>
				<input type="text" name="q" id="q" placeholder="search..." value="<?=isset($_GET["q"])?str_replace("-"," ",$_GET["q"]):""?>" />
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
				<input type="submit" value="GO" />
						</fieldset>
                    </form>
                </div>
            </div>
            <div class="navigation">
                <li><a href="/">Home</a></li> | <li><a href="/request-for-content">Request for content</a></li>
            </div>