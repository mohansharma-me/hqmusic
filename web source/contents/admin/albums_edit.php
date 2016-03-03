<?php
$aid=filterInput(INPUT_GET,"id");
if(!isset($aid) || (isset($aid) && !is_numeric($aid))) {
?>
<div class='window'>
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
            //http://www.omdbapi.com/?i=tt2310332&plot=full&r=json
        ?>
    </div>
    <div class='content'>
        <h2>Sorry, given album id is invalid.</h2>
        <form class='form1'>
        <fieldset>
        <input type='button' onClick="document.location='/admin/albums'" value='GO BACK'/>
        </fieldset>
        </form>
    </div>
</div>
<?php
exit;
}

?>
<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
        ?>
    </div>
    <?php
    $err=array();
    $noPoster=false;
    //filterInput($type, $name, $sqlParam, $lowerit, $trim)
    $action=filterInput(INPUT_POST,"action");
    $released=filterInput(INPUT_POST,"released");
    $genre=filterInput(INPUT_POST,"genre",true,false,true);
    $director=filterInput(INPUT_POST,"director",true,false,true);
    $writer=filterInput(INPUT_POST,"writer",true,false,true);
    $category=filterInput(INPUT_POST,"sub-category",true,false,true);
    $name=filterInput(INPUT_POST,"name",true,false,true);
    $slug=filterInput(INPUT_POST,"slug");
    $year=filterInput(INPUT_POST,"year",true,false,true);
    $actors=filterInput(INPUT_POST,"actors",true,false,true);
    $description=filterInput(INPUT_POST,"plot",true,false,true);
    $poster=filterInput(INPUT_POST,"poster",true,false,true);
    $imdb_poster=filterInput(INPUT_POST,"imdb_poster",true,false,true);
    $uploadedPoster=filterInput(INPUT_POST,"uploadedPoster");
    $flag=false;
    if(!isset($action)) {
        $res=sql("select * from albums where album_id=$aid");
        if(mysql_affected_rows()==1) {
            $row=mysql_fetch_assoc($res);
            $category=$row["album_category_id"];
            $name=$row["album_name"];
            $description=$row["album_description"];
            $slug=$row["album_slug"];
            $year=$row["album_year"];
            $actors=$row["album_cast"];
            $imdb_json=($row["album_imdb_json"]);
	    $album_art=trim($row["album_art"]);
	    if(strlen($album_art)!=0) {
		$noPoster=true;
		$imdb_poster="/theme/qrcode.png";
	    } else {
		$imdb_poster="/images/$slug"."_original";
            }
	    $json=json_decode(stripslashes($imdb_json),true);
            if(is_array($json) && count($json)>0) {
                if(isset($json["released"])) {
                    $released=$json["released"];
                }
                if(isset($json["genre"])) {
                    $genre=ucwords($json["genre"]);
                }
                if(isset($json["director"])) {
                    $director=ucwords($json["director"]);
                }
                if(isset($json["writer"])) {
                    $writer=ucwords($json["writer"]);
                }
            }
        } else {
            echo "<div class='content'><h2>Sorry, given album id is not valid..</h2></div></div>";
            exit;
        }
    } else if(!isset($category) || (isset($category) && !is_numeric($category))) {
        $err["category"]="<font  style='background:red'>Invalid Category</font>";
    } else if(!isset($name) || (isset($name) && empty($name))) {
        $err["name"]="<font  style='background:red'>Empty Name</font>";
    } else if(!isset($slug) || (isset($slug) && empty($slug))) {
        $err["slug"]="<font  style='background:red'>Empty Slug</font>";
    } else if(preg_match("/[^0-9a-zA-Z-]/", $slug)!=0) {
        $err["slug"]="<font  style='background:red'>Invalid Slug</font>";
    } else if(!isset($year) || (isset($year) && (empty($year) || !is_numeric($year)))) {
        $err["year"]="<font  style='background:red'>Empty/Invalid Year</font>";
    } else if(!isset($actors) || (isset($actors) && (empty($actors)))) {
        $err["actors"]="<font  style='background:red'>Empty Actors</font>";
    } else if(!isset($description) || (isset($description) && (empty($description)))) {
        $err["plot"]="<font  style='background:red'>Empty Description</font>";
    } else if(isset($uploadedPoster)) {
        if(!isset($_FILES["poster"]) || (isset($_FILES["poster"]) && ($_FILES["poster"]["type"]!="image/jpeg" && $_FILES["poster"]["type"]!="image/png" && $_FILES["poster"]["type"]!="image/jpg")))  {
            if(!isset($_FILES["poster"])) {
                $err["poster"]="<font  style='background:red'>No Picture</font>";
            } else {
                $err["poster"]="<font  style='background:red'>Only JPEG, PNG.</font>";
            }
        } else {
            $flag=true;
        }
    } else if(!isset($uploadedPoster) && isset($imdb_poster) && empty($imdb_poster)) {
        $err["poster"]="IMDB Poster Invalid.";
    } else {
        $flag=true;
    }
    
    if($flag) {
        $res=sql("select * from albums where album_id!=$aid and (lower(album_slug)='$slug')");
        if(mysql_affected_rows()>0) {
            $err["slug"]="<font style='background:red'>Already Exists.</font>";
        } else if(mysql_affected_rows()==0) {
            $js=array();
            $imdb_json=filterInput(INPUT_POST,"imdb_json");
            if(!isset($imdb_json)) {
                // what if json isn't provided
            } else {
                //update json with modified data
                $js=json_decode(base64_decode($imdb_json),true);
            }
            $res=sql("select albums.*,a.category_slug as sub,b.category_slug as sup from albums, (select * from categories where category_parent_id!=0) a,(select * from categories where category_parent_id=0) b where album_id=$aid and a.category_id=album_category_id and b.category_id=a.category_parent_id");
            if(mysql_affected_rows()==1) {
                $row=mysql_fetch_assoc($res);
                $sup=$row["sup"];
                $sub=$row["sub"];
                $al_slug=$row["album_slug"];
                $olddir=_ROOT_."/files/$sup/$sub/$al_slug";
                
				$old_posters["300"]=_ROOT_."/images/$al_slug"."_300";
				$old_posters["300_wwm"]=_ROOT_."/images/$al_slug"."_300_wwm";
				$old_posters["thumb"]=_ROOT_."/images/$al_slug"."_thumb";
				$old_posters["original"]=_ROOT_."/images/$al_slug"."_original";
				
				$new_posters["300"]=_ROOT_."/images/$slug"."_300";
				$new_posters["300_wwm"]=_ROOT_."/images/$slug"."_300_wwm";
				$new_posters["thumb"]=_ROOT_."/images/$slug"."_thumb";
				$new_posters["original"]=_ROOT_."/images/$slug"."_original";
				
				if(file_exists($old_posters["300"])) rename($old_posters["300"],$new_posters["300"]);
				if(file_exists($old_posters["300_wwm"])) rename($old_posters["300_wwm"],$new_posters["300_wwm"]);
				if(file_exists($old_posters["thumb"])) rename($old_posters["thumb"],$new_posters["thumb"]);
				if(file_exists($old_posters["original"])) rename($old_posters["original"],$new_posters["original"]);
				
				$a_art="";
				if($imdb_poster=="/theme/qrcode.png" && !isset($uploadedPoster)) $a_art="false";
				echo $imdb_poster." == $a_art";
				$res=sql("update albums set album_category_id=$category,album_name='$name',album_description='$description',album_slug='$slug',album_year='$year',album_art='$a_art',album_cast='$actors' where album_id=$aid");
                if(mysql_affected_rows()>=0) {
					$res=sql("SELECt a.category_slug as sub, b.category_slug as sup from (select * from categories where category_parent_id!=0) a, (select * from categories where category_parent_id=0) b where b.category_id=a.category_parent_id and a.category_id=$category");
                    $row=mysql_fetch_assoc($res);
                    $catslug=$row["sub"];
                    $pcatslug=$row["sup"];

                    $dir=_ROOT_."/files/$pcatslug/$catslug/$slug";
                    rename($olddir, $dir);
					
					$or_fn="";
					$uploadedFile="";
					$transFlag=false;
					
					if(isset($uploadedPoster)) { // new uploaded image
						$or_fn=basename($_FILES["poster"]["name"]);
						$uploadedFile=$_FILES["poster"]["tmp_name"];
						$path=_ROOT_."/images/$slug"."_original";
						move_uploaded_file($uploadedFile,$path);
						$uploadedFile=$path;
					} else if(isset($imdb_poster)) {
						if(substr($imdb_poster,0,1)=="/") {  //old imdb/uploaded poster
							if($imdb_poster=="/theme/qrcode.png") {
								$noPoster=true;
							} else {
								$uploadedFile=_ROOT_.$imdb_poster;
								$or_fn=basename($uploadedFile);
							}
						} else { // new imdb poster
							$headers=get_headers($imdb_poster);
							if($headers==false) {
								if(!file_exists($new_posters["original"])) {
									sql("update albums set album_art='false' where album_id=$aid");
									$noPoster=true;
								} else {
									$uploadedFile=$new_posters["original"];
									$or_fn=basename($uploadedFile);
								}
							} else {
								$or_fn=str_replace("Content-Type: ","",$headers[count($headers)-1]);
								$file=file_get_contents($imdb_poster);
								file_put_contents(_ROOT_."/images/$slug"."_original",$file);
								$uploadedFile=_ROOT_."/images/$slug"."_original";
								$transFlag=true;
							}
						}
					}
					

					try {
						if(!$noPoster) {
						$jpeg=false;
						$png=false;
						$img=null;
						
						if(substr($or_fn,strlen($or_fn)-4,4)=="jpeg" || substr($or_fn,strlen($or_fn)-3,3)=="jpg") {
							$img=imagecreatefromjpeg($uploadedFile);
							$jpeg=true;
						} else if(substr($or_fn,strlen($or_fn)-3,3)=="png") {
							$img=imagecreatefrompng($uploadedFile);
							$png=true;
						}

						$wm_path="./theme/watermark.png";
						$wm=imagecreatefrompng($wm_path);
						list($w1,$h1)=getimagesize($wm_path);

					
						if($jpeg || $png) {
							$ori_size=600;
							list($wid,$hei)=  getimagesize($uploadedFile);
							$o_w=$wid;
							$o_h=$hei;
							if($wid>$hei) {
								$hei=($hei*$ori_size)/$wid;
								$wid=$ori_size;
								//$hei=300*0.75;
							} else {
								$wid=($wid*$ori_size)/$hei;
								$hei=$ori_size;
								//$wid=300*0.75;
							}
							$thumb=imagecreatetruecolor($wid, $hei);
							
							imagecopyresized($thumb, $img, 0, 0, 0, 0, $wid, $hei, $o_w, $o_h);
							//imagecopyresized($thumb,  $wm, 0, $hei-$h1, 0, 0, $wid,$h1,$w1,$h1);
							if($jpeg) {
								imagejpeg($thumb, _ROOT_."/images/$slug"."_300_wwm",100);
							} else if($png) {
								imagepng($thumb, _ROOT_."/images/$slug"."_300_wwm",9);
							}
							
							imagecopyresized($thumb,  $wm, 0, $hei-$h1, 0, 0, $wid,$h1,$w1,$h1);
							if($jpeg) {
								imagejpeg($thumb, _ROOT_."/images/$slug"."_300",100);
							} else if($png) {
								imagepng($thumb, _ROOT_."/images/$slug"."_300",9);
							}
							
							imagedestroy($thumb);
							
							$ori_size=64;
							list($wid,$hei)=getimagesize($uploadedFile);
							$o_w=$wid;
							$o_h=$hei;
							if($wid>$hei) {
								$hei=($hei*$ori_size)/$wid;
								$wid=$ori_size;
								//$hei=300*0.75;
							} else {
								$wid=($wid*$ori_size)/$hei;
								$hei=$ori_size;
								//$wid=300*0.75;
							}
							$thumb=imagecreatetruecolor($wid, $hei);
							
							imagecopyresized($thumb, $img, 0, 0, 0, 0, $wid, $hei, $o_w, $o_h);
							if($jpeg) {
								imagejpeg($thumb, _ROOT_."/images/$slug"."_thumb",100);
							} else if($png) {
								imagepng($thumb, _ROOT_."/images/$slug"."_thumb",9);
							}
							imagedestroy($thumb);
							
							if($transFlag) {
								//unlink($uploadedFile);
							}
						} else {
							
						}
						$imdb_poster="/images/$slug"."_300";
						$err["gen"]="<font style='background:green'>Successfully saved.</font>";
						}
					} catch(Exception $e) {
						
					}
                } else {
					$err["gen"]="<font  style='background:red'>Not Saved, Please Try Again.</font>";
				}
            } else {
                $err["gen"]="<font style='background:red'>Invalid Category and Album Relation.</font>";
            }
        }
    }
    ?>
    <div class='content'>
        <form class="form1" method="post" id="formAddAlbum" enctype="multipart/form-data">
            <h2 class="lefty">Edit Album</h2>
            <?php
            if(isset($err["gen"])) {
                echo "<br/><br/><span>".$err["gen"]."</span>";
            }
            ?>
            <br/><br/>
            <div class="lefty">
                <fieldset>
                    <label>Sub-Category: *</label> <?=isset($err["category"])?$err["category"]:""?>
                    <select name="sub-category">
                        <option style="display: none" selected>Select Category</option>
                        <?php
                        $res=sql("select category_id, category_name from categories where category_parent_id!=0");
                        if(mysql_affected_rows()>0) {
                            while($row=mysql_fetch_assoc($res)) {
                                if(isset($category) && $row["category_id"]==$category) {
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
                    <label>Album Name: *</label> <?=isset($err["name"])?$err["name"]:""?>
                    <input type="text" value="<?=stripslashes($name)?>" name="name" placeholder="album name" />
                </fieldset>
                <fieldset>
                    <label>Album Slug: *</label> <?=isset($err["slug"])?$err["slug"]:""?>
                    <input type="text" value="<?=stripslashes($slug)?>" name="slug" placeholder="album slug" />
                </fieldset>
                <fieldset>
                    <label>Year: *</label> <?=isset($err["year"])?$err["year"]:""?>
                    <input type="text" value="<?=stripslashes($year)?>" name="year" placeholder="album year" />
                </fieldset>
                <fieldset>
                    <label>Released:</label> <?=isset($err["released"])?$err["released"]:""?>
                    <input type="text" value="<?=stripslashes($released)?>" name="released" placeholder="released date" />
                </fieldset>
                <fieldset>
                    <label>Genre:</label> <?=isset($err["genre"])?$err["genre"]:""?>
                    <input type="text" value="<?=stripslashes($genre)?>" name="genre" placeholder="genre" />
                </fieldset>
                <fieldset>
                    <label>Director:</label> <?=isset($err["director"])?$err["director"]:""?>
                    <input type="text" value="<?=stripslashes($director)?>" name="director" placeholder="director" />
                </fieldset>
                <fieldset>
                    <label>Writer:</label> <?=isset($err["writer"])?$err["writer"]:""?>
                    <input type="text"  value="<?=stripslashes($writer)?>"name="writer" placeholder="album writer" />
                </fieldset>
                <fieldset>
                    <label>Actors: *</label> <?=isset($err["actors"])?$err["actors"]:""?>
                    <input type="text"  value="<?=stripslashes($actors)?>"name="actors" placeholder="album actors" />
                </fieldset>
                <fieldset>
                    <label>Description (Plot): *</label> <?=isset($err["plot"])?$err["plot"]:""?>
                    <input type="text" name="plot"  value="<?=stripslashes($description)?>" placeholder="album description" />
                </fieldset>
                <fieldset>
                    <label>Poster: *</label> <?=isset($err["poster"])?$err["poster"]:""?>
                    <input type="hidden" name="imdb_json" value="<?=base64_encode($imdb_json)?>" />
                    <input type="hidden" name="imdb_poster" value="<?=stripslashes($imdb_poster)?>" />
                    <input type="checkbox" name="uploadedPoster" style="width:auto" <?=isset($uploadedPoster)?"checked":""?> /><input value="<?=$poster?>" type="file" name="poster" placeholder="album poster" style="width:auto" />                    
                </fieldset>
                
                <fieldset>
                    <input type="submit" name="action" value="SAVE" />
                    <input class="righty" type="button" value="BACK" onClick="document.location='/admin/albums'" />
                </fieldset>

            </div>
            <div class="lefty" style="margin-left:120px">
                <fieldset>
                    <label>IMDB Fetch:</label>
                    <input type="text" id="imdbFetchID" placeholder="IMDB ID" /><br/>
                    <input type="button" id="btnFetchIMDB" value="Fetch" />
                </fieldset>
                <fieldset>
                    <label id='posterImageLoader'>Poster:</label><br/>
                    <img src="<?=stripslashes($imdb_poster)?>" id="posterImage" style='width:240px;border:1px solid white;' />
                </fieldset>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#albumFetchMethod").change(function() {
        if($(this).val()===0) {
            
        }
    });
});
</script>