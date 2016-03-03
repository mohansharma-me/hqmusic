<div class="content-holder">
    <div class="content">
        <?php
			$res=sql("select * from (select * from updates order by update_date desc) updates, albums where update_album_id=album_id order by rand() limit 10");
			if(mysql_affected_rows()>0) {
		?>
		<ul class="style-list1">
            <h1>Latest Updates</h1>
			<?php
			while($row=mysql_fetch_assoc($res)) {
				$name=$row["album_name"];
				$slug=$row["album_slug"];
				$acid=$row["album_category_id"];
				$parent_name="";
				$res1=sql("select sup.category_name as sup, sub.category_name as sub, sup.category_slug as sup_slug, sub.category_slug as sub_slug from (select * from categories where category_parent_id!=0) sub, (select * from categories where category_parent_id=0) sup where sup.category_id=sub.category_parent_id and sub.category_id=$acid");
				if(mysql_affected_rows()==1) {
					$row1=mysql_fetch_assoc($res1);
					$parent_name="<a ahref='/".$row1["sup_slug"]."/".$row1["sub_slug"]."' style='color:black'><b>".$row1["sub"]."</b></a>";
				}
				echo "<li>$parent_name : <label><a href='/$slug'>$name</a></label></li>";
			}
			?>
        </ul>
		<?php
			}
		?>

        <ul class="style-list1 categories">
            <h1>Categories</h1>
            <?php
            //$res=sql("select * from categories where category_parent_id=0");
			$res=sql("select b.*,a.category_slug as parent_slug from (select * from categories where category_parent_id=0) a, (select * from categories where category_parent_id!=0) b where a.category_id=b.category_parent_id");
            if(is_numeric($res) && $res==0) {
                echo "<li><label><font color='green'><b>No Categories.</b></font></label></li>";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'><b>Can't connect to database.</b></font></label></li>";
            } else if(is_resource($res)) {
                while($row=mysql_fetch_assoc($res)) {
                    $name=$row["category_name"];
                    $slug=trim($row["category_slug"]);
					$pslug=trim($row["parent_slug"]);
                    echo "<li><label><a href='/$pslug/$slug'>$name</a></label></li>";
                }
            }
            ?>
        </ul>
    </div>
</div>
            