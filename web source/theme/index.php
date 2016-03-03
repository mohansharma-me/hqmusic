<?php
session_start();
include_once './inc/inc.functions.php';
if(!isset($_SESSION["isMobile"])) {
    $_SESSION["isMobile"]=isMobile();
}
$isMobile=$_SESSION["isMobile"];

$keys=array();
$key=filterInput(INPUT_GET,"__key");
if(isset($key)) {
    $keys=explode("/",$key);
}

if(count($keys)>0 && strtolower(trim($keys[0]))=="admin") {
    require "./admin.php";
} else {
    $pm=preg_match("/^123\.237\.225\.\d{1,3}\z/", $_SERVER["REMOTE_ADDR"])==0;
    if($_SERVER["HTTP_HOST"]=="localhost:90" || !$pm || isset($_GET["debug"])) {
        require "./mobile.php";
    } else if($isMobile) {
        require "./desktop.php";
    }
}
