<script>
$(document).ready(function() {
    $(".open-dialog").click($.openDialog);
    $.checkCounter=0;
    
    $.trEvent=function() {
        var tr=$(this).parent();
        var chk=$(tr).find("td:first-child").find("input[type='checkbox']");
        var cur=$(chk).prop("checked");
        $(chk).prop("checked",!cur);
        cur=!cur;
        if(cur) {
            $.checkCounter++;
            $(tr).css({borderLeft:"2px solid #2A6FBD"});
        } else {
            $.checkCounter--;
            $(tr).css({borderLeft:"2px solid transparent"});
        }
        if($.checkCounter===0) {
            $("#cat_delete_btn").slideUp();
        } else {
            $("#cat_delete_btn").slideDown();
        }
    };
    
    $("#cat_table tr.clickable td").click($.trEvent);
    
    $("#cat_delete_btn").click(function() {
        var ids="";
        var names="";
        $("#cat_table").find("input[type='checkbox']").each(function(i,e) {
            if($(this).prop("checked")) {
                if(ids==="") {
                    ids+=$(this).attr("catid");
                } else {
                    ids+=","+$(this).attr("catid");
                }
                if(names==="") {
                    names+=$(this).attr("cat");
                } else {
                    names+="\n"+$(this).attr("cat");
                }
            }
        });
        var flag=confirm("Are you sure to delete following categories from server ?\n"+names);
        if(flag) {
            reqPost("/admin/service",{service:"delete-category",ids:ids},function(data) {
                try {
                    var js=$.parseJSON(data);
                    if(js.success) {
                        $("#cat_table tr").each(function() {
                            var tr=$(this);
                            $(this).find("input[type='checkbox']");
                        });
                        alert(js.deleted+" categorie(s) deleted.\n\nFollowing folders are removed :: \n"+js.paths_deleted+"\n\nFollowing folders are NOT removed:: \n"+js.paths_not_deleted);
                        $(".dlink[href='categories']").click();
                    } else {
                        alert("Can't delete selected categories.\nError:"+js.error);
                    }
                } catch(e) {}
            });
        }
    });
    
    $("#rootCat").change(function() {
        var select=$(this);
        var pid=$(select).val();
        $(select).toggleDisabled();
        reqPost("/admin/service",{service:"get-sub-category",parent_id:pid},function(data) {
            $("#cat_table").html("<tr><th>Name</th><th>Slug</th><th style='width:10%'>Action</th></tr>"+data);
            $("#cat_table tr.clickable td").click($.trEvent);
            $(".open-dialog").click($.openDialog);
            $(select).toggleDisabled();
            $("#newCatBtn").attr("data",'{"parent_id":'+pid+'}');
        });
    });
});
</script>
<div class='header'>
    <h1 class='title'>Categories</h1>
</div>
<div class='content'>
    <table class="table1">
        <tr class="buttons">
            <td colspan="1">
                <select id="rootCat" style="padding:5px 15px">
                    <?php
                    $res=sql("select * from categories where category_parent_id=0");
                    if(mysql_affected_rows()>0) {
                        echo "<option value='0' selected>ROOT</option>";
                        while($row=mysql_fetch_assoc($res)) {
                            echo "<option value='".$row["category_id"]."'>".$row["category_name"]."</option>";
                        }
                    } else {
                        echo "<option disabled>NO ROOT</option>";
                    }
                    ?>
                </select>
                <input id="root_delete_btn" type="button" value="DELETE ROOT" />
            </td>            
            <td colspan="2" style="text-align: right">
                <input style="display:none" id="cat_delete_btn" type="button" value="DELETE" />
                <input type="button" class="open-dialog" title="New Category" page="new-category" value="NEW CATEGORY" data='{"parent_id":0}' id='newCatBtn' />
            </td>
        </tr>
    </table>
    <table class="table1" id="cat_table">
        <tr><th>Name</th><th>Slug</th><th style='width:10%'>Action</th></tr>
        <?php
        //$res=sql("SELECT a.*,if(a.category_parent_id=0,'ROOT',b.category_name) as parent FROM `categories` a left join categories b on b.category_id=a.category_parent_id order by category_id desc");
        $res=sql("select * from categories where category_parent_id=0");
        if(mysql_affected_rows()>0) {
            while($row=mysql_fetch_assoc($res)) {
                $name=ucwords($row["category_name"]);
                $slug=strtolower($row["category_slug"]);
                $arr["catid"]=$row["category_id"];
                $data=json_encode($arr);
                echo "<tr class='clickable'><td><input type='checkbox' cat='$name' catid='".$row["category_id"]."' id='cat_checks' /> &nbsp;$name</td><td>$slug</td><td class='buttons' style='text-align:center'>";
                ?><input type='button' class="open-dialog" title="Edit Category" page="edit-category" value='Edit Category' data='<?=$data?>' /><?php echo "</td></tr>";
            }
        }
        ?>
    </table>
    
    <div style="display: none">
        <div id="my-dialog" title=""></div>
    </div>
</div>