<div class="content-holder">
    <div class="content">
        <ul class="style-list1 categories">
            <h1><?=$myData["category_name"]?></h1>
            <?php
            $res=sql("select * from categories where category_parent_id=".$myData["category_id"]);
            if(is_numeric($res) && $res==0) {
                echo "<li><label><font color='green'><b>No Categories.</b></font></label></li>";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'><b>Can't connect to database.</b></font></label></li>";
            } else if(is_resource($res)) {
                $parent_category_slug=trim($myData["category_slug"]);
                while($row=mysql_fetch_assoc($res)) {
                    $name=$row["category_name"];
                    $slug=trim($row["category_slug"]);
                    echo "<li><label><a href='/$parent_category_slug/$slug'>$name</a></label></li>";
                }
            }
            ?>
        </ul>
        
        <ul class="style-list1">
            <li style="text-align: center">
                <a href="/">Home</a> &gt;&gt; 
                <?php 
                    echo "<a href='/".$myData["category_slug"]."'>".$myData["category_name"]."</a>";
                ?>
            </li>
        </ul>
    </div>
</div>
            