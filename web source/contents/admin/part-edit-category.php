<?php
$cat=array();
if(isset($data)) {
    $data=json_decode(stripslashes($data),true);
    $res=sql("select * from categories where category_id=".$data["catid"]);
    if(mysql_affected_rows()>0) {
        $cat=mysql_fetch_assoc($res);
    } else {
        echo "<font color='red'>Category not found.</font>";
        exit;
    }
} else {
    echo "<font color='red'>Invalid Category-Edit Request.</font>";
    exit;
}
?>
<script>
$(document).ready(function() {
    //alert(str.match(/[^0-9a-zA-Z-]/)?"Y":"F");
    $("#frmAddCategory #name,#frmAddCategory #slug").change(function() {
        $(this).css({borderColor:"#333"});
    });
    $("#frmAddCategory").submit(function() {
        var form=$(this);
        var parent=$(this).find("#parent").val();
        var name=$(this).find("#name").val();
        var slug=$(this).find("#slug").val();
        if(parent===null) {
            $(this).find("#parent").css({borderColor:"red"});
            $(this).find("#parent").focus();
        } else if($.trim(name).length===0) {
            $(this).find("#name").css({borderColor:"red"});
            $(this).find("#name").focus();
        } else if($.trim(slug).length===0) {
            $(this).find("#slug").css({borderColor:"red"});
            $(this).find("#slug").focus();
        } else {
            var res=$.trim(slug).match(/[^0-9a-zA-Z-]/);
            if(res===null) {
                reqPost("/admin/service",{service:"edit-category",category_slug:slug,category_parent_id:parent,category_name:name,category_id:<?=$cat["category_id"]?>},function(data) {
                    try {
                        var js=$.parseJSON(data);
                        if(js.success) {
                            alert("Saved");
                            $(form).find("#parent").html(js.new_categories);
                            $(form).find("#name").val("");
                            $(form).find("#slug").val("");
                            $(form).find("#name").focus();
                            $("#rootCat").change();
                            //$(".dlink[href='categories']").click();
                        } else {
                            if(js.error==="already exists") {
                                alert("Given category name or slug is already exits.");
                            } else if(js.error==="invalid data") {
                                alert("Invalid new category provided data.");
                            } else if(js.error==="invalid slug") {
                                alert("Please check company slug didn't contain any symbolic charactor.");
                            } else {
                                alert("Error in response from server.\nError:"+js.error);
                            }
                        }
                    } catch(e) {}
                });
            } else {
                alert("Please check your slug.\nIt must contain only alphabets, numbers and dash.");
            }
        }
    });
});
</script>
<form class="full-width" action="javascript:void" id="frmAddCategory">
    <label>Parent Category:</label>
    <select id="parent" style="width:100%">
        <option value="0">ROOT</option>
        <?php
        $res=sql("select * from categories where category_parent_id=0");
        if(is_resource($res)) {
            while($row=mysql_fetch_assoc($res)) {
                if($row["category_id"]==$cat["category_parent_id"]) {
                    echo "<option value='".$row["category_id"]."' selected>".ucwords($row["category_name"])."</option>";
                } else if($row["category_id"]!=$cat["category_id"]) {
                    echo "<option value='".$row["category_id"]."'>".ucwords($row["category_name"])."</option>";
                }
            }
        }
        ?>
    </select><br/><br/>
    <label>Category Name:</label>
    <input type="text" id="name" placeholder="category name" value="<?=$cat["category_name"]?>" /><br/><br/>
    <label>Category Slug:</label>
    <input type="text" id="slug" placeholder="category slug" value="<?=$cat["category_slug"]?>" /><br/><br/>
    <input type="submit" value="Save" style="background:#ccc;color:black" />
</form>