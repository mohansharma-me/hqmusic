<?php
$data=array();
$data["success"]=false;
$service=filterInput(INPUT_POST,"service");
if(isset($service)) {
    if($service=="get-sub-category") {
        $parent=filterInput(INPUT_POST,"parent_id");
        if(isset($parent) && is_numeric($parent)) {
            $res=sql("select * from categories where category_parent_id=$parent");
            if(mysql_affected_rows()>0) {
                while($row=mysql_fetch_assoc($res)) {
                    $name=$row["category_name"];
                    $slug=$row["category_slug"];
                    $arr["catid"]=$row["category_id"];
                    $data1=json_encode($arr);
                    echo "<tr class='clickable'><td><input type='checkbox' cat='$name' catid='".$row["category_id"]."' id='cat_checks' /> &nbsp;$name</td><td>$slug</td><td class='buttons' style='text-align:center'>";
                    ?><input type='button' class="open-dialog" title="Edit Category" page="edit-category" value='Edit Category' data='<?=$data1?>' /><?php echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='3'><font color='red'>No Sub-Categories.</font></td></tr>";
            }
            exit;
        }
    } else 
    if($service=="add-category") {
        $parent=filterInput(INPUT_POST,"category_parent_id",true,false,true);
        $slug=filterInput(INPUT_POST,"category_slug",true,true,true);
        $name=filterInput(INPUT_POST,"category_name",true,false,true);
        if(isset($parent) && isset($name) && isset($slug) && is_numeric($parent) && !empty($name) && !empty($slug)) {
            $pm=preg_match("/[^0-9a-zA-Z-]/", $slug)==0;
            if($pm) {
                $res=sql("select * from categories where (lower(category_name)='$name' and category_parent_id=$parent) or lower(category_slug)='$slug'");
                if(mysql_affected_rows()>0) {
                    $data["error"]="already exists";
                } else {
                    $savepoint=date("dmYHisua",strtotime("now"));
                    sql("start transaction");
                    sql("savepoint $savepoint");
                    $res=sql("insert into categories(category_name,category_slug,category_parent_id) values('$name','$slug',$parent)");
                    if(mysql_affected_rows()==1) {
                        $res=sql("select category_slug from categories where category_id=$parent");
                        if(mysql_affected_rows()==1 || $parent==0) {
                            $parent_category_slug="";
                            if($parent>0) {
                                $row=mysql_fetch_assoc($res);
                                $parent_category_slug=trim($row["category_slug"])."/";
                            }
                            $folderpath="./files/$parent_category_slug".$slug;
                            if((!is_dir($folderpath) && mkdir($folderpath, 0755, true)) || is_dir($folderpath)) {
                                $data["success"]=true;
                                $data["new_categories"]="<option value='0'>ROOT</option>";
                                $res=sql("select * from categories where category_parent_id=0");
                                if(mysql_affected_rows()>0) {
                                    while($row=mysql_fetch_assoc($res)) {
                                        $data["new_categories"].="<option value='".$row["category_id"]."'>".ucwords($row["category_name"])."</option>";
                                    }
                                }
                                sql("release savepoint $savepoint");
                                sql("commit");
                            } else {
                                $data["error"]="mkdir access denied";
                                sql("rollback to savepoint $savepoint");
                                sql("release savepoint $savepoint");
                            }
                        } else {
                            $data["error"]="invalid parent category";
                            sql("rollback to savepoint $savepoint");
                            sql("release savepoint $savepoint");
                        }
                    } else {
                        $data["error"]="invalid database access";
                        sql("rollback to savepoint $savepoint");
                        sql("release savepoint $savepoint");
                    }
                }
            } else {
                $data["error"]="invalid slug";
            }
        } else {
            $data["error"]="invalid data";
        }
    } else if($service=="edit-category") {
        //filterInput($type, $name, $sqlParam, $lowerit, $trim)
        $catid=filterInput(INPUT_POST,"category_id",true,false,true);
        $parent=filterInput(INPUT_POST,"category_parent_id",true,false,true);
        $slug=filterInput(INPUT_POST,"category_slug",true,true,true);
        $name=filterInput(INPUT_POST,"category_name",true,false,true);
        if(isset($catid) && isset($parent) && isset($name) && isset($slug) && is_numeric($parent) && !empty($name) && !empty($slug)) {
            $pm=preg_match("/[^0-9a-zA-Z-]/", $slug)==0;
            if($pm) {
                $res=sql("select * from categories where ((lower(category_name)='$name' and category_parent_id=$parent) or lower(category_slug)='$slug') and category_id!=$catid");
                if(mysql_affected_rows()>0) {
                    $data["error"]="already exists";
                } else {
                    //$res=sql("insert into categories(category_name,category_slug,category_parent_id) values('$name','$slug',$parent)");
                    $res=sql("update categories set category_name='$name', category_slug='$slug', category_parent_id='$parent' where category_id=$catid");
                    $data["success"]=true;
                    $data["new_categories"]="<option value='0'>ROOT</option>";
                    $res=sql("select * from categories where category_parent_id=0");
                    if(mysql_affected_rows()>0) {
                        while($row=mysql_fetch_assoc($res)) {
                            $data["new_categories"].="<option value='".$row["category_id"]."'>".ucwords($row["category_name"])."</option>";
                        }
                    }
                }
            } else {
                $data["error"]="invalid slug";
            }
        } else {
            $data["error"]="invalid data";
        }
    } else if($service=="delete-category") {
        $data=array();
        $data["success"]=false;
        $ids=filterInput(INPUT_POST,"ids");
        if(isset($ids)) {
            $res=sql("select category_id,category_slug,category_parent_id from categories where category_id in($ids)");
            if(mysql_affected_rows()>0) {
                $sp=date("dmYhisua",strtotime("now"));
                sql("start transaction");
                sql("savepoint $sp");
                $paths=array();
                $_ids="";
                $flag=true;
                $delete_count=0;
                $queries=array();
                while($row=mysql_fetch_assoc($res)) {
                    $id=$row["category_id"];
                    $slug=$row["category_slug"];
                    $parent=$row["category_parent_id"];
                    if($parent==0) {
                        $paths[]="./files/$slug";
                        $res1=sql("select category_id,category_slug from categories where category_parent_id=$id");
                        if(mysql_affected_rows()>0) {
                            $temp_ids="";
                            while($row1=mysql_fetch_assoc($res1)) {
                                $paths[]="./files/$slug/".$row1["category_slug"];
                                $temp_ids.=",".$row1["category_id"];
                            }
                            $temp_ids=substr(1,strlen($temp_ids)-1);
                            $queries[]="delete from categories where category_id in($temp_ids)";
                        } else if(mysql_affected_rows()<0) {
                            $flag=false;
                            break;
                        }
                        //sql("delete from categories where category_id=$parent or category_parent_id=$parent");
                        $queries[]="delete from categories where category_id=$parent or category_parent_id=$parent";
                    } else {
                        $res1=sql("select category_slug from categories where category_id=$parent");
                        if(mysql_affected_rows()==1) {
                            $row1=mysql_fetch_assoc($res1);
                            $paths[]="./files/".$row1["category_slug"]."/$slug";
                        } else if(mysql_affected_rows()<0) {
                            $flag=false;
                            break;
                        }
                        //sql("delete from categories where category_id=$id");
                        $queries[]="delete from categories where category_id=$id";
                    }
                }
                
                if($flag) {
                    $non_deleted_paths="";
                    $deleted_paths="";
                    foreach($paths as $path) {
                        if(deleteDirectory($path)) {
                            $deleted_paths.="$path\n";
                        } else {
                            $non_deleted_paths.="$path\n";
                        }
                    }
                    $data["paths_deleted"]=strlen($deleted_paths)==0?"NON":$deleted_paths;
                    $data["paths_not_deleted"]=json_encode($queries); //strlen($non_deleted_paths)==0?"NON":$non_deleted_paths;
                    $data["deleted"]=$delete_count;
                    $data["success"]=true;
                    //sql("rollback to savepoint $sp");
                    sql("release savepoint $sp");
                    sql("commit");
                } else {
                    $data["error"]="unable to perform delete operation on database";
                    sql("rollback to savepoint $sp");
                    sql("release savepoint $sp");
                }
                //delete now
                /*sql("delete from categories where category_id in ($ids) or category_parent_id in ($ids)");
                if(mysql_affected_rows()==-1) {
                    $data["error"]="cant delete";
                } else {
                    $data["success"]=true;
                    $data["deleted"]=  mysql_affected_rows();
                }*/
            } else {
                $data["error"]="no categories belongs to this";
            }
        } else {
            $data["error"]="invalid data";
        }
    }
} else {
    $data["error"]="invalid service";
}
echo json_encode($data);