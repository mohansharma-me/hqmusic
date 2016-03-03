<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <div class='content'>
        <table class="table1" id="parent_cat_table">
            <tr class="buttons">
                <td colspan="3">
                    <input class="lefty" type="button" value="NEW" onClick="document.location='/admin/sub-categories/new'" />
                    <input class="righty editCat" onClick="$.editSubCategories()" type="button" value="EDIT" />
                    <input class="righty deleteCat" type="button" onClick="$.deleteSubCategories()" value="DELETE" />
                </td>
            </tr>
            <tr><th>Name</th><th>Slug</th><th>Parent</th></tr>
            <?php
            $res=sql("select a.*,if(b.category_name=null,'Main',b.category_name) as parent_name from (select * from categories where category_parent_id!=0) a left join (select * from categories where category_parent_id=0) b on b.category_id=a.category_parent_id");
            if(mysql_affected_rows()>0) {
                while($row=mysql_fetch_assoc($res)) {
                    $name=ucwords($row["category_name"]);
                    $slug=strtolower($row["category_slug"]);
                    $parent=$row["parent_name"];
                    $arr["catid"]=$row["category_id"];
                    $data=json_encode($arr);
                    echo "<tr class='clickable'><td><input class='parent_cat_chk' type='checkbox' cat='$name' catid='".$row["category_id"]."' id='cat_checks' /> &nbsp;$name</td><td>$slug</td><td>$parent</td></tr>";
                }
            }
            ?>
        </table>

    </div>
</div>