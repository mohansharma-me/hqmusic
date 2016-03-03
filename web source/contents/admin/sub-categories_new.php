<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <div class='content'>
        <?php
        $err_gen="";
        $err_name="";
        $err_pname="";
        $err_slug="";
        $pid=filterInput(INPUT_POST,"parent_category");
        $name=filterInput(INPUT_POST,"name",true,false,true);
        $slug=filterInput(INPUT_POST,"slug");
        if(isset($name) && isset($slug) && isset($pid) && !empty($name) && !empty($slug) && !empty($pid) && is_numeric($pid)) {
            $pm=preg_match("/[^0-9a-zA-Z-]/", $slug)==0;
            if($pm) {
                $res=sql("select if(lower(category_slug)='$slug',1,2) as result from categories where category_parent_id=$pid and (lower(category_slug)='$slug')");
                if(mysql_affected_rows()>0) {
                    $row=mysql_fetch_assoc($res);
                    if($row["result"]=="1") {
                        $err_slug="<font style='background:red;color:white;float:right'>Already exists.</font>";
                    } else {
                        $err_name="<font style='background:red;color:white;float:right'>Already exists.</font>";
                    }
                } else if(mysql_affected_rows()==0) {
                    $res=sql("select * from categories where category_id=$pid");
                    if(mysql_affected_rows()==1) {
                        $row=mysql_fetch_assoc($res);
                        $pslug=$row["category_slug"];
                        $dir=_ROOT_."/files/$pslug/$slug";
                        if(!file_exists($dir) && !is_dir($dir)) {
                            if(mkdir($dir,0755,true)) {
                                $dir=true;
                            } else {
                                $dir=false;
                            }
                        } else {
                            $dir=true;
                        }
                        if($dir) {
                            $res=sql("insert into categories(category_name,category_slug,category_parent_id) values('$name','$slug',$pid)");
                            if(mysql_affected_rows()>0) {
                                $err_gen="<font style='background:green'>Successfully added.</font>";
                            } else {
                                $err_gen="<font style='background:red'>Please try again.</font>";
                            }
                        } else {
                            $err_gen="<font style='background:red'>MKDIR failed.</font>";
                        }
                    } else {
                        $err_pname="<font style='background:red;float:right'>* Invalid Category</font>";
                    }
                } else {
                    $err_gen="<font  style='background:red'>Please try again!</font>";
                }
            } else {
                $err_slug="<font style='background:red;color:white;float:right'>Invalid Slug</font>";
            }
        }
        ?>
        <form class="form1" method="post">
            <fieldset>
                <h2>New Sub-Category</h2> <span><?=$err_gen?></span>
            </fieldset>
            <fieldset>
                <label>Parent Category:* </label><?=$err_pname?><br/>
                <select name="parent_category">
                    <?php
                    $res=sql("select * from categories where category_parent_id=0");
                    if(mysql_affected_rows()>0) {
                        while($row=mysql_fetch_assoc($res)) {
                            if($row["category_id"]==$pid) {
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
                <input type="text" name="name" placeholder="category name" value="<?=$name?>" />
            </fieldset>
            <fieldset>
                <label>Category Slug:* </label><?=$err_slug?><br/>
                <input type="text" name="slug" placeholder="category slug (only alphabets, -)" value="<?=$slug?>" />
            </fieldset>
            <fieldset>
                <input type="submit" value="ADD NOW" />
                <input class="righty" type="button" value="BACK" onClick="document.location='/admin/sub-categories'" />
            </fieldset>
        </form>
    </div>
</div>