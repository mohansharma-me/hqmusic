<?php
include_once "./inc/inc.admin.functions.php";
$logout=filterInput(INPUT_GET, "logout");
if(isset($logout)) {
    dropAuthentication();
}

$myTitle=null;
$myHead=null;
$contentPage="./contents/admin/homepage.php";
$myData=array();
$myData["keys"]=$keys;
if(count($keys)!=0 && isset($keys[1])) {
    $k1_arr=array("categories","sub-categories","albums","songs","profile","files","updates");
    $k2_arr=array("new","edit","delete","search");
    $k1=strtolower(trim($keys[1]));
    $flag=true;
    if(strpos($k1,".")==FALSE && in_array($k1,$k1_arr)) {
        $flag=$flag && true;
        $pagefile="./contents/admin/$k1";
        if(isset($keys[2])) {
            $k2=strtolower(trim($keys[2]));
            if(strpos($k2,".")==FALSE && in_array($k2,$k2_arr)) {
                $pagefile.="_$k2";
                $flag=$flag && true;
            } else {
                $flag=$flag && false;
            }
        }
    } else {
        $flag=$flag && false;
    }

    if($flag) {
        $pagefile.=".php";
        if(file_exists($pagefile) && is_file($pagefile)) {
            if(isset($k1) && isset($k2)) {
                $myTitle=ucwords($k2)." - ".ucwords($k1)." - ".$_SESSION["adminData"]["name"]." - HQMusic.in";
            } else {
                $myTitle=ucwords($k1)." - ".$_SESSION["adminData"]["name"]." - HQMusic.in";
            }
            $contentPage=$pagefile;
        }
    }
}

if(isAuthed()) {
    loadAdminContent($myTitle, $myHead, $contentPage, $myData);
} else {
    $errorMessage="";
    $flag=false;
    if(count($_POST)>0) {
        $flag=getAuthenticated();
        if(!$flag) {
            $errorMessage="<b><font color='red'>*</font> <font color='white'>Failed</font></b><br/></br>";
        } else {
            header("Location: /admin/categories");
        }
    }
    if($flag) {
        loadAdminContent($myTitle, $myHead, $contentPage, $myData);
    } else {
        $myData["username"]="";
        if(isset($_POST["username"])) {
            $myData["username"]=filterInput(INPUT_POST,"username");
        }
        $myData["errorMessage"]=$errorMessage;
        loadAdminContent("Authentication - HQMusic.in", "", "./contents/admin/login.php",$myData);
    }
}