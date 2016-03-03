<head>
	<?php
	if(isset($myTitle)) {
	?>
	<title><?=$myTitle?></title>
	<?php
	} else {
	?>
    <title>HQMusic.in</title>
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
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'>
    
    <link rel="favicon" href="/theme/favicon.ico" />
    
    <link rel="stylesheet" href="/theme/css/boostrap.css">
    <link rel="stylesheet" href="/theme/css/font_awesome.css">
    <link rel="stylesheet" href="/theme/css/styles.css">
    <!-- IE 8 Fallback -->
    <!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />
	<![endif]-->

	<link rel="stylesheet" href="/theme/css/mystyles.css">
	
	<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">
stLight.options({publisher: "05780768-7745-48f3-b256-629f8b37e893", doNotHash: false, doNotCopy: false, hashAddressBar: false});
</script>

    <?php
	include_once "./script.header.php";
	?>
</head>