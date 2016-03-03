<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <div class='content'>
        <form style="width:70%;margin:0px auto" class="form1 center">
            <fieldset>
        <?php
        $yes=filterInput(INPUT_GET,"delete");
        $ids=filterInput(INPUT_GET,"ids");
        if(isset($ids) && !isset($yes)) {
            $res=sql("select * from albums where album_id in($ids)");
            if(mysql_affected_rows()>0) {
                echo "<h2>Are you sure to delete following albums ?</h2><br/>";
                echo "<div style='padding-left:20px'>";
                while($row=mysql_fetch_assoc($res)) {
                    echo "<li>".$row["album_name"]."</li>";
                }
                echo "<input type='hidden' name='ids' value='$ids' />";
                echo "<input type='hidden' name='delete' value='yes' />";
                echo "</div><br/><span>Note: be careful, because it will deleted along with its all songs directly.</span>";
            } else {
                echo "<h2>Sorry, there isn't any album match with given data.</h2>";
            }
        } else if(isset($ids) && isset($yes)) {
            $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug as album_slug from categories sup 
                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                left join albums on album_category_id=sub.category_id
                left join songs on song_album_id=album_id
                left join files on file_song_id=song_id where album_id in($ids)");
            if(mysql_affected_rows()>0) {
                $dirs=array();
				$slugs300=array();
				$slugsthumb=array();
				$slugsori=array();
                while($row=mysql_fetch_assoc($res)) {
                    $dirs[]=_ROOT_."/files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"];
					$slugs300[]=_ROOT_."/images/".$row["album_slug"]."_300";
					$slugsthumb[]=_ROOT_."/images/".$row["album_slug"]."_thumb";
					$slugsori[]=_ROOT_."/images/".$row["album_slug"]."_original";
                }
                $res=sql("delete albums,songs,files from categories sup 
                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                left join albums on album_category_id=sub.category_id
                left join songs on song_album_id=album_id
                left join files on file_song_id=song_id 
                where album_id in($ids)");
                if(mysql_affected_rows()>0) {
                    echo "<h2>Following folders are deleted:</h2><br/>";
                    echo "<div style='padding-left:40px'>";
					$i=0;
                    foreach($dirs as $dir) {
                        $f=deleteDirectory($dir);
						if(file_exists($slugs300[$i])) {
							unlink($slugs300[$i]);
						}
						if(file_exists($slugsthumb[$i])) {
							unlink($slugsthumb[$i]);
						}
						if(file_exists($slugsori[$i])) {
							unlink($slugsori[$i]);
						}
						$i++;
                        if($f) {
                            echo "<li><font color='white'>$dir - <u>DELETED</u></font></li>";
                        } else {
                            echo "<li><font color='black'>$dir - <u>NOT DELETED</u></font></li>";
                        }
                    }
                    echo "</div>";
                } else {
                    echo "<h2>Sorry, there isn't any matching albums with given data.</h2>";
                }
            }
        } else {
            echo "<h2>Sorry, invalid request.</h2>";
        }
        ?>
            </fieldset>
            <fieldset>
                <?php
                if(isset($ids) && !isset($yes)) {
                ?>
                <input class="lefty" type="submit" value="YES" onclick="document.location='/admin/albums'" />
                <?php
                }
                ?>
                <input class="righty" type="button" value="GO BACK" onclick="document.location='/admin/albums'" />
            </fieldset>
        </form>
    </div>
</div>