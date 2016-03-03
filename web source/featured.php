<?php
$output=array();
$output["success"]=false;
session_start();
include_once "./inc/inc.functions.php";
include_once "./inc/inc.admin.functions.php";
if(isAuthed()) {
	$func=filterInput(INPUT_POST,"func");
	if(isset($func)) {
		$aid=filterInput(INPUT_POST,"album_id");
		if(isset($aid) && $func=="add") {
			$res=sql("insert into updates(update_album_id) values($aid)");
			$output["success"]=true;
		} else if(isset($aid) && $func=="delete") {
			$res=sql("delete from updates where update_album_id=$aid");
			$output["success"]=true;
		}
	}
}
echo json_encode($output);