<?php
include_once "./inc/inc.functions.php";
$q=filterInput(INPUT_POST,"q");
$instantSearch=filterInput(INPUT_POST,"instantSearch");
if(isset($q)) {
    /*$res=sql("select albums.*,updates.*,sub.category_name as sub,sup.category_name as sup from categories sup 
            left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
            left join albums on album_category_id=sub.category_id
            left join songs on song_album_id=album_id
            left join files on file_song_id=song_id
			left join updates on update_album_id=album_id
            where
            album_id>0 and (
            (song_slug LIKE '$q%' or song_slug LIKE '%$q' or song_slug LIKE '$q') or
            (song_name LIKE '$q%' or song_name LIKE '%$q' or song_name LIKE '$q') or
            (album_slug LIKE '$q%' or album_slug LIKE '%$q' or album_slug LIKE '$q') or
            (album_name LIKE '$q%' or album_name LIKE '%$q' or album_name LIKE '$q') or
            (sup.category_slug LIKE '$q%' or sup.category_slug LIKE '%$q' or sup.category_slug LIKE '$q') or
            (sub.category_slug LIKE '$q%' or sub.category_slug LIKE '%$q' or sub.category_slug LIKE '$q') or
            (sub.category_name LIKE '$q%' or sub.category_name LIKE '%$q' or sub.category_name LIKE '$q')
            ) group by album_id");*/

	$res=sql("select albums.*,updates.* from albums
	    left join updates on update_album_id=album_id
            where
            album_id>0 and (
            (album_name LIKE '$q%' or album_name LIKE '%$q' or album_name LIKE '$q'))");
	
    if(mysql_affected_rows()>0) {
		if(!isset($instantSearch)) {
			echo "<tr><th>Name</th><th>Slug</th><th>Category</th><th>Featured</th></tr>";
		}
        while($row=mysql_fetch_assoc($res)) {
            /*$name=$row["song_name"];
            $slug=$row["song_slug"];
            $album_name=$row["sup"]."=>".$row["sub"]."=>".$row["album_name"];
            //echo "<tr><td>$song_name</td><td>$song_slug</td><td>$album_name</td></tr>";
            echo "<tr class='clickable'><td><input class='parent_cat_chk' type='checkbox' cat='$name' catid='".$row["song_id"]."' id='cat_checks' /> &nbsp;$name</td><td>$slug</td><td>$album_name <input class='righty' type='button' value=' FILES ' onClick=\"document.location='/admin/songs/new/?songid=".$row["song_id"]."'\" /></td></tr>";*/
			$aid=$row["album_id"];
			$name=ucwords($row["album_name"]);
			$slug=strtolower($row["album_slug"]);
			$cat=$row["sup"]." => ".$row["sub"];
			$arr["catid"]=$row["album_id"];
			$data=json_encode($arr);
			$featured="<input type='button' class='addToFeatured' data-album-id='$aid' value='Add to Featured' />";
			if(isset($row["update_album_id"])) {
				$featured="<input type='button' class='delFromFeatured' data-album-id='$aid' value='Remove from Featured' />";
			}
			if(!isset($instantSearch)) {
				echo "<tr class='clickable'><td><input class='parent_cat_chk' type='checkbox' cat='$name' catid='".$row["album_id"]."' id='cat_checks' /> &nbsp;$name</td><td>$slug</td><td>$cat</td><td class='buttons'>$featured</td></tr>";
			} else {
				echo "<li><a href='javascript:void' data-album-id='$aid' data-album-slug='$slug' data-album-name='$name'><img src='/images/$slug"."_thumb' /> $name</a></li>";
			}
        }
		echo '<script>$(".addToFeatured").click($.addToFeatured);
			$(".delFromFeatured").click($.delFromFeatured);</script>';
    } else if(mysql_affected_rows()==0) {
        echo "<tr><td colspan=3><center>No Results Found.</center></td></tr>";
    } else {
        echo "<tr><td colspan=3><center>Error While Search.</center></td></tr>";
    }
}