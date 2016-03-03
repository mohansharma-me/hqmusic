<div class="content-holder">
    <div class="content">
        <ul class="style-list1 categories">
            <h1><img style="width:25%" src="/images/<?=$myData["album_slug"]?>_300_wwm.png" /><br/><?=$myData["album_name"]?> </h1>
            <?php
            $res=sql("select * from songs s where lower(s.song_album_id)='".$myData["album_id"]."'");
            if(is_resource($res)) {
                $album_slug=strtolower(trim($myData["album_slug"]));
                while($row=mysql_fetch_assoc($res)) {
                    $name=ucwords($row["song_name"]);
                    $slug=strtolower(trim($row["song_slug"]));
                    echo "<li><label><a href='/$album_slug/$slug'>$name</a></label></li>";
                }
				echo '<br/><span>'.$myData["album_description"].'</span><br/><br/>';
            } else if(is_numeric($res) && $res==0) {
                echo "<li><label><font color='green'>No Songs.</font></label></li>";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'>Can't connect to database.</font></label></li>";
            }
            ?>
        </ul>
        
        <ul class="style-list1">
            <?php
            $res=sql("select category_slug from categories where category_id=".$myData["category_parent_id"]);
            $link="/";
            if(is_resource($res)) {
                $row=mysql_fetch_assoc($res);
                $link="/".trim(strtolower($row["category_slug"]));
            } else if(is_numeric($res) && $res==0) {
                $link="/";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'>Can't connect to database.</font></label></li>";
            }
            if($link!="/") {
                $link.="/".trim(strtolower($myData["category_slug"]));
            }
            ?>
            <li style="text-align: center">
                <a href="/">Home</a> &gt;&gt;
                <?php
                    //<a href='$link&lt;&lt; <?=ucwords($myData["category_name"])
                    $parent_cat_slug="";
                    $res=sql("select * from categories c where c.category_id=".$myData["category_parent_id"]);
                    if(is_resource($res)) {
                        $row=mysql_fetch_assoc($res);
                        $parent_cat_slug="/".$row["category_slug"];
                        echo "<a href='/".$row["category_slug"]."'>".$row["category_name"]."</a> &gt;&gt;";
                    }
                    echo " <a href='$parent_cat_slug/".$myData["category_slug"]."'>".$myData["category_name"]."</a> &gt;&gt;";
                    echo " <a href=''>".$myData["album_name"]."</a>";
                ?>
            </li>
        </ul>
    </div>
</div>
            