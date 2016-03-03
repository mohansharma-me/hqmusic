<?php
$output=array();
$output["success"]=false;
session_start();
include_once "./inc/inc.functions.php";
include_once "./inc/inc.admin.functions.php";
if(isAuthed()) {
    $songid=$_REQUEST["songid"];
    if(isset($_FILES["mp3file"]) && isset($songid) && is_numeric($songid)) {
        $file=$_FILES["mp3file"];
        $type=$file["type"];
        $size=$file["size"];
        $error=$file["error"];
        if($error=="0") {
            $ext=strtolower(substr($file["name"],strlen($file["name"])-3,3));
            $_type=strtolower(substr($type,0,5));
            if($_type=="audio") {
                $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug,song_slug from categories sup 
                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                left join albums on album_category_id=sub.category_id
                left join songs on song_album_id=album_id
                left join files on file_song_id=song_id 
                where 
                song_id=$songid");
                
                if(mysql_affected_rows()>0) {
                    $row=mysql_fetch_assoc($res);
                    $savedir="./files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"]."/".$row["song_slug"];
                    echo $savedir;
                }
            }
        }
    }
}
echo json_encode($output);