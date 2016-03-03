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
        $err_slug="";
        $name=filterInput(INPUT_POST,"name",true,false,true);
        $slug=filterInput(INPUT_POST,"slug");
        if(isset($name) && isset($slug) && !empty($name) && !empty($slug)) {
            $pm=preg_match("/[^0-9a-zA-Z-]/", $slug)==0;
            if($pm) {
                $res=sql("select if(lower(category_slug)='$slug',1,2) as result from categories where lower(category_slug)='$slug'");
                if(mysql_affected_rows()>0) {
                    $row=mysql_fetch_assoc($res);
                    if($row["result"]=="1") {
                        $err_slug="<font style='background:red;color:white;float:right'>Already exists.</font>";
                    } else {
                        $err_name="<font style='background:red;color:white;float:right'>Already exists.</font>";
                    }
                } else if(mysql_affected_rows()==0) {
                    $dir=_ROOT_."/files/$slug";
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
                        $res=sql("insert into categories(category_name,category_slug,category_parent_id) values('$name','$slug',0)");
                        if(mysql_affected_rows()>0) {
                            $err_gen="<font style='background:green'>Successfully added.</font>";
                        } else {
                            $err_gen="<font style='background:red'>Please try again.</font>";
                        }
                    } else {
                        $err_gen="<font style='background:red'>MKDIR failed.</font>";
                    }
                } else {
                    $err_gen="<font style='background:red'>Please try again!</font>";
                }
            } else {
                $err_slug="<font style='background:red;color:white;float:right'>Invalid Slug</font>";
            }
        }
        ?>
        <form class="form1" method="post">
            <fieldset>
                <h2>New Category</h2> <span><?=$err_gen?></span>
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
                <input class="righty" type="button" value="BACK" onClick="document.location='/admin/categories'" />
            </fieldset>
        </form>
    </div>
</div>