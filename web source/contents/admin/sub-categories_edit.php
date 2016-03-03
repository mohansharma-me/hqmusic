<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <div class='content'>
        <?php
        $_name="";
        $_slug="";
        $_pid="";
        
        $err_gen="";
        $err_name="";
        $err_pname="";
        $err_slug="";
        
        $id=filterInput(INPUT_GET,"id");
        if(isset($id) && is_numeric($id)) {
            $old_slug="";
            $res=sql("select a.*,b.category_id as parent_id,if(b.category_name=null,'Main',b.category_name) as parent_name,if(b.category_slug=null,'',b.category_slug) as parent_slug from (select * from categories where category_parent_id!=0) a left join (select * from categories where category_parent_id=0) b on a.category_id=$id and b.category_id=a.category_parent_id");
            if(mysql_affected_rows()>0) {
                $row=mysql_fetch_assoc($res);
                $_name=$row["category_name"];
                $_slug=$row["category_slug"];
                $_pid=$row["parent_id"];
                $_pname=$row["parent_name"];
                $_pslug=$row["parent_slug"];
                
                $old_slug=$_pslug."/".$row["category_slug"];
                
                $name=filterInput(INPUT_POST,"name",true,false,true);
                $slug=filterInput(INPUT_POST,"slug");
                $pid=filterInput(INPUT_POST,"parent_category");
                if(isset($name) && isset($slug) && isset($pid) && !empty($name) && !empty($slug) && !empty($pid) && is_numeric($pid)) {
                    $_name=$name;
                    $_slug=$slug;
                    $_pid=$pid;
                    $pm=preg_match("/[^0-9a-zA-Z-]/", $slug)==0;
                    if($pm) {
                        $res=sql("select if(lower(category_slug)='$slug',1,2) as result from categories where category_id!=$id and category_parent_id=$_pid and (lower(category_slug)='$slug')");
                        if(mysql_affected_rows()>0) {
                            $row=mysql_fetch_assoc($res);
                            if($row["result"]=="1") {
                                $err_slug="<font style='background:red;color:white;float:right'>Already exists.</font>";
                            } else {
                                $err_name="<font style='background:red;color:white;float:right'>Already exists.</font>";
                            }
                        } else if(mysql_affected_rows()==0) {
                            $res=sql("select category_slug from categories where category_id=$pid");
                            if(mysql_affected_rows()==1) {
                                $row=mysql_fetch_assoc($res);
                                $new_parent_slug=$row["category_slug"];
                                $dir=_ROOT_."/files/$new_parent_slug/$slug";
                                if(true) {
                                    if(rename(_ROOT_."/files/".$old_slug,$dir)) {
                                        $dir=true;
                                    } else {
                                        $dir=false;
                                    }
                                } else {
                                    $dir=true;
                                }
                                if($dir) {
                                    $res=sql("update categories set category_name='$name', category_slug='$slug', category_parent_id=$pid where category_id=$id");
                                    if(mysql_affected_rows()>0) {
                                        $err_gen="<font style='background:green'>Successfully saved.</font>";
                                    } else {
                                        $err_gen="<font style='background:red'>Please try again.</font>";
                                    }
                                } else {
                                    $err_gen="<font style='background:red'>MOVE failed.</font>";
                                }
                            } else {
                                $err_pname="<font style='background:red;float:right'>Invalid Parent Category Selection.</font>";
                            }
                        } else {
                            $err_gen="<font  style='background:red'>Please try again!</font>";
                        }
                    } else {
                        $err_slug="<font style='background:red;color:white;float:right'>Invalid Slug</font>";
                    }
                }
            } else {
                $err_gen="<font style='background:red'>Invalid Category.</font>";
            }
        } else if(isset($id) && !is_numeric($id)) {
            $err_gen="<font style='background:red'>Invalid Category!</font>";
        }
        ?>
        <form class="form1" method="post">
            <fieldset>
                <h2>Edit Category</h2> <span><?=$err_gen?></span>
            </fieldset>
            <?php
            if($err_gen!="<font style='background:red'>Invalid Category.</font>" && $err_gen!="<font style='background:red'>Invalid Category!</font>") {
            ?>
            <fieldset>
                <label>Parent Category:* </label><?=$err_pname?><br/>
                <select name="parent_category">
                    <?php
                    $res=sql("select * from categories where category_parent_id=0");
                    if(mysql_affected_rows()>0) {
                        while($row=mysql_fetch_assoc($res)) {
                            if($row["category_id"]==$_pid) {
                                echo "<option value='".$row["category_id"]."' selected>".$row["category_name"]."</option>";
                            } else {
                                echo "<option value='".$row["category_id"]."'>".$row["category_name"]."</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </fieldset>

            <fieldset>
                <label>Category Name:* </label><?=$err_name?><br/>
                <input type="text" name="name" placeholder="category name" value="<?=$_name?>" />
            </fieldset>
            <fieldset>
                <label>Category Slug:* </label><?=$err_slug?><br/>
                <input type="text" name="slug" placeholder="category slug (only alphabets, -)" value="<?=$_slug?>" />
            </fieldset>
            <fieldset>
                <input type="submit" value="SAVE" />
                <input class="righty" type="button" value="BACK" onClick="document.location='/admin/categories'" />
            </fieldset>
            <?php
            } else {
            ?>
            <fieldset>
                <input class="lefty" type="button" value="BACK" onClick="document.location='/admin/sub-categories'" />
            </fieldset>
            <?php
            }
            ?>
        </form>
    </div>
</div>