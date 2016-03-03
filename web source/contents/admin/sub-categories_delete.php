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
            $res=sql("select * from categories where category_parent_id!=0 and category_id in($ids)");
            if(mysql_affected_rows()>0) {
                echo "<h2>Are you sure to delete following categories ?</h2><br/>";
                echo "<div style='padding-left:20px'>";
                while($row=mysql_fetch_assoc($res)) {
                    echo "<li>".$row["category_name"]."</li>";
                }
                echo "<input type='hidden' name='ids' value='$ids' />";
                echo "<input type='hidden' name='delete' value='yes' />";
                echo "</div><br/><span>Note: be careful, because it will deleted along with its albums and songs directly.</span>";
            } else {
                echo "<h2>Sorry, there isn't any sub-categories match with given data.</h2>";
            }
        } else if(isset($ids) && isset($yes)) {
            $res=sql("select a.category_slug as sub_slug,if(b.category_slug=null,'',b.category_slug) as sup_slug from (select * from categories where category_parent_id!=0) a inner join (select * from categories where category_parent_id=0) b on b.category_id=a.category_parent_id where a.category_id in($ids)");
            if(mysql_affected_rows()>0) {
                $dirs=array();
                while($row=mysql_fetch_assoc($res)) {
                    $dirs[]=_ROOT_."/files/".$row["sup_slug"]."/".$row["sub_slug"];
                }
                $res=sql("delete sub,albums,songs,files from categories sup 
                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                left join albums on album_category_id=sub.category_id
                left join songs on song_album_id=album_id
                left join files on file_song_id=song_id 
                where sup.category_parent_id=0 and sub.category_id in($ids)");
                if(mysql_affected_rows()>0) {
                    echo "<h2>Following folders are deleted:</h2><br/>";
                    echo "<div style='padding-left:40px'>";
                    foreach($dirs as $dir) {
						if(file_exists($dir)) {
                        $f=deleteDirectory($dir);
                        if($f) {
                            echo "<li><font color='white'>$dir - <u>DELETED</u></font></li>";
                        } else {
                            echo "<li><font color='black'>$dir - <u>NOT DELETED</u></font></li>";
                        }
						}
                    }
                    echo "</div>";
                } else {
                    echo "<h2>Sorry, there isn't any matching sub-categories with given data.</h2>";
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
                <input class="lefty" type="submit" value="YES" onclick="document.location='/admin/sub-categories'" />
                <?php
                }
                ?>
                <input class="righty" type="button" value="GO BACK" onclick="document.location='/admin/sub-categories'" />
            </fieldset>
        </form>
    </div>
</div>