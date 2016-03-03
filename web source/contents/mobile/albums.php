<div class="content-holder">
    <div class="content">
        <ul class="style-list1">
            <h1>Albums of <?=$myData["category_name"]?></h1>
            <?php
			$noi=10;
			$page=1;
			if(isset($_GET["page"]) && is_numeric($_GET["page"])) {
				$page=$_GET["page"];
			}
			$start=($page-1)*$noi;
            $res=sql("select SQL_CALC_FOUND_ROWS album_id,albums.* from albums where album_category_id=".$myData["category_id"]." order by album_art, album_id desc limit $start,$noi");
            if(is_numeric($res) && $res==0) {
                echo "<li><label><font color='green'><b>No Categories.</b></font></label></li>";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'><b>Can't connect to database.</b></font></label></li>";
            } else if(is_resource($res)) {
				$res1=sql("select found_rows() as total_rows");
				$totalRows=mysql_fetch_assoc($res1)["total_rows"];
                while($row=mysql_fetch_assoc($res)) {
                    $name=$row["album_name"];
                    $desc=$row["album_description"];
                    $slug=trim($row["album_slug"]);
                    echo "<li><label><a href='/$slug'><img src='/images/$slug"."_thumb.png' /> $name</a></label></li>";
				}
				$parent_slug=$myData["p_category_slug"];
				$category_slug=$myData["category_slug"];
				$mainLink="/$parent_slug/$category_slug/";
				$totalPages=ceil($totalRows/$noi);
				/*$prvPage=$page;
				$nxtPage=$page;
				if($page=="1") {
					$prvPage=2;
				}
				$prvLink=$mainLink."?page=".($prvPage-1);
				if($page+1>$totalPages) {
					$nxtPage=$totalPages-1;
				}
				$nxtLink=$mainLink."?page=".($nxtPage+1);*/
            }
            ?>
			<div style="text-align:center">
					<?php
					/*if($page>1) {
						echo "<a href='$prvLink' class='btn btn-primary'>PREVIOUS</a> ";
					}
					if($page<$totalPages) {
						echo "<a href='$nxtLink' class='btn btn-primary'>NEXT</a>";
					}*/
					
					$start=$page-4;
			if($start<1) {
				$start=1;
			}
			$end=$start+9;
			if($start>=2) {
				echo '<a href="?page=1" class="btn">1</a> ... ';
			}
			$lastPage=$totalPages;
			for($i=$start;$i<$end && $i<=$lastPage;$i++) {
				$link="?page=$i";
				$btnP="";
				if($i==$page) {
					$btnP=" btn-primary";
				}
				echo '<a href="'.$link.'" class="btn'.$btnP.'">'.$i.'</a> ';
			}
			if($page+4<$lastPage)
			echo ' ... <a href="?page='.$lastPage.'" class="btn">'.$lastPage.'</a>';
					
					?>
				
			</div>
        </ul>

        <ul class="style-list1">
            <li style="text-align: center">
                <a href="/">Home</a> &gt;&gt;
                <?php
                    echo "<a href='/".$myData["p_category_slug"]."'>".$myData["p_category_name"]."</a> &gt;&gt;";
                    echo " <a href='/".$myData["p_category_slug"]."/".$myData["category_slug"]."'>".$myData["category_name"]."</a>";
                ?>
            </li>
        </ul>
    </div>
</div>
            