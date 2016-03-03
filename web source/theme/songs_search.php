<?php
include_once "./inc/inc.functions.php";
$q=filterInput(INPUT_POST,"q");
if(isset($q)) {
    $res=sql("select songs.*,files.*,albums.*,sub.category_name as sub,sup.category_name as sup from categories sup 
            left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
            left join albums on album_category_id=sub.category_id
            left join songs on song_album_id=album_id
            left join files on file_song_id=song_id
            where
            song_id>0 and (
            (song_slug LIKE '$q%' or song_slug LIKE '%$q' or song_slug LIKE '$q') or
            (song_name LIKE '$q%' or song_name LIKE '%$q' or song_name LIKE '$q') or
            (album_slug LIKE '$q%' or album_slug LIKE '%$q' or album_slug LIKE '$q') or
            (album_name LIKE '$q%' or album_name LIKE '%$q' or album_name LIKE '$q') or
            (sup.category_slug LIKE '$q%' or sup.category_slug LIKE '%$q' or sup.category_slug LIKE '$q') or
            (sub.category_slug LIKE '$q%' or sub.category_slug LIKE '%$q' or sub.category_slug LIKE '$q') or
            (sub.category_name LIKE '$q%' or sub.category_name LIKE '%$q' or sub.category_name LIKE '$q')
            )");
    if(mysql_affected_rows()>0) {
        echo "<tr><th>Name</th><th>Slug</th><th>Album</th></tr>";
        while($row=mysql_fetch_assoc($res)) {
            $name=$row["song_name"];
            $slug=$row["song_slug"];
            $album_name=$row["sup"]."=>".$row["sub"]."=>".$row["album_name"];
            //echo "<tr><td>$song_name</td><td>$song_slug</td><td>$album_name</td></tr>";
            echo "<tr class='clickable'><td><input class='parent_cat_chk' type='checkbox' cat='$name' catid='".$row["song_id"]."' id='cat_checks' /> &nbsp;$name</td><td>$slug</td><td>$album_name <input class='righty' type='button' value=' FILES ' onClick=\"document.location='/admin/songs/new/?songid=".$row["song_id"]."'\" /></td></tr>";
        }
    } else if(mysql_affected_rows()==0) {
        echo "<tr><td colspan=3><center>No Results Found.</center></td></tr>";
    } else {
        echo "<tr><td colspan=3><center>Error While Search.</center></td></tr>";
    }
}