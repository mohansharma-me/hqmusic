<?php
session_start();
define("_ROOT_",$_SERVER["DOCUMENT_ROOT"]);
include_once './inc/inc.functions.php';
if(!isset($_SESSION["isMobile"])) {
    $_SESSION["isMobile"]=isMobile();
}
$isMobile=$_SESSION["isMobile"];
$cView=filterInput(INPUT_GET,"view");
if(isset($cView)) {
	if($cView=="mobile") {
		$_SESSION["customView"]=true;
	} else if($cView=="desktop") {
		$_SESSION["customView"]=false;
	} else {
		$_SESSION["customView"]=$isMobile;
	}
}
if(isset($_SESSION["customView"])) {
	define("_MOBILE_",$_SESSION["customView"]);
} else {
	define("_MOBILE_",$isMobile);
}

$keys=array();
$key=filterInput(INPUT_GET,"__key");
if(isset($key)) {
    $keys=explode("/",addslashes($key));
}

if(count($keys)>0 && strtolower(trim($keys[0]))=="admin") {
    require "./admin.php";
} else {
    if(_MOBILE_) {
        require "./mobile.php";
    } else {
        require "./desktop.php";
    }
}
