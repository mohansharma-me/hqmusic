<?php
$output=array();
$output["success"]=false;
session_start();
include_once "./inc/inc.functions.php";
include_once "./inc/inc.admin.functions.php";
if(isAuthed()) {
    $fileid=filterInput(INPUT_POST,"fileid");
    if(isset($fileid) && is_numeric($fileid)) {
        $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug,song_slug,kbps from categories sup 
                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                left join albums on album_category_id=sub.category_id
                left join songs on song_album_id=album_id
                left join files on file_song_id=song_id 
                where 
                file_id=$fileid");
        if(mysql_affected_rows()==1) {
            $row=mysql_fetch_assoc($res);
            $path="./files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"]."/".$row["song_slug"]."/".$row["kbps"].".mp3";
            $res=sql("delete files from categories sup 
                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                left join albums on album_category_id=sub.category_id
                left join songs on song_album_id=album_id
                left join files on file_song_id=song_id 
                where 
                file_id=$fileid");
            if(file_exists($path) && is_file($path))  {
                unlink($path);
            }
            echo "Deleted";
        } else {
            echo "Wrong file id, please try again.";
        }
    }
}