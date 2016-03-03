<div class="window">
    <div class='header'>
        <?php
            include_once "./contents/admin/icon-links.php";
            //http://www.omdbapi.com/?i=tt2310332&plot=full&r=json
        ?>
    </div>
    <?php
    $err=array();
    $err["uploaded_files"]=array();
    $err["transloaded_files"]=array();
    //filterInput($type, $name, $sqlParam, $lowerit, $trim)
    $songid=filterInput(INPUT_GET,"songid");
    if(isset($songid) && is_numeric($songid)) {
        $res=sql("select * from songs,albums where song_id=$songid and album_id=song_album_id");
        if(mysql_affected_rows()==1) {
            $song=mysql_fetch_assoc($res);
            $action=filterInput(INPUT_POST,"action");
            if(isset($action)) {
                if($action=="upload") {
                    $kbps=$_POST["kbps1"];
                    if(isset($_FILES["mp3file"]) && is_array($kbps)) {
                        $nof=count($_FILES["mp3file"]["name"]);
                        for($i=0;$i<$nof;$i++) {
                            $file=$_FILES["mp3file"];
                            $name=$file["name"][$i];
                            $type=$file["type"][$i];
                            $size=$file["size"][$i];
                            $error=$file["error"][$i];
                            if($error=="0") {
                                $ext=strtolower(substr($name,strlen($name)-3,3));
                                $_type=strtolower(substr($type,0,5));
                                if($_type=="audio") {
                                    $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug,song_slug from categories sup 
                                    left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                                    left join albums on album_category_id=sub.category_id
                                    left join songs on song_album_id=album_id
                                    left join files on file_song_id=song_id 
                                    where 
                                    song_id=$songid");

                                    if(mysql_affected_rows()>0) {
										//echo "Flag1<br/>";
                                        $row=mysql_fetch_assoc($res);
                                        $kbps[$i]=trim($kbps[$i]);
                                        if(isset($kbps[$i]) && !empty($kbps[$i])) {
											//echo "Flag2<br/>";
                                            $kb=$kbps[$i];
                                            $savedir=_ROOT_."/files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"]."/".$row["song_slug"];
                                            if(!file_exists($savedir)) {
                                                mkdir($savedir,0755,true);
                                            }
                                            $savedir="$savedir/$kb.mp3";
											//echo $savedir."<br/>";
                                            if(move_uploaded_file($file["tmp_name"][$i], $savedir)) {
                                                $imgPath=_ROOT_."/images/".$song["album_slug"]."_300";
						if(!file_exists($imgPath)) {
							$imgPath=_ROOT_."/theme/qrcode.png";
						}
						if(replaceIDTags($savedir, $imgPath,$song)) {
                                                    //$size=round(($size/1024)/1024)." MB";
                                                    $size=formatBytes($size);
                                                    sql("insert into files(file_song_id,kbps,file_size) values($songid,'$kb','$size')");
                                                    if(mysql_affected_rows()==1) {
                                                        $err["uploaded_files"][$i]="<font style='background:green'>OK</font>";
                                                    }
                                                } else {
                                                    $err["uploaded_files"][$i]="<font style='background:red'>ID3Tag Failed</font>";
                                                }
                                            } else {
                                                $err["uploaded_files"][$i]="<font style='background:red'>Upload Failed</font>";
                                            }
                                        } else {
                                            $err["uploaded_files"][$i]="<font style='background:red'>KBPS ?</font>";
                                        }
                                    } else {
                                        $err["uploaded_files"][$i]="<font style='background:red'>Invalid Song</font>";
                                    }
                                } else {
                                    $err["uploaded_files"][$i]="<font style='background:red'>Not MP3</font>";
                                }
                            } else {
                                $err["uploaded_files"][$i]="<font style='background:red'>Failed</font>";
                            }
                        }
                    }
                } else if($action=="transload") {
                    $kbps=$_POST["kbps2"];
                    if(isset($_POST["linkfile"]) && is_array($kbps)) {
                        $nof=count($_POST["linkfile"]);
                        for($i=0;$i<$nof;$i++) {
                            $file=trim($_POST["linkfile"][$i]);
                            if(empty($file)) {
                                continue;
                            }
                            $flag=false;
                            $data="";
                            try {
                                $data=file_get_contents($file);
                                $flag=true;
                            } catch(Exception $e) {
                                $flag=false;
                            }
                            if($flag) {
                                $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug,song_slug from categories sup 
                                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                                left join albums on album_category_id=sub.category_id
                                left join songs on song_album_id=album_id
                                left join files on file_song_id=song_id 
                                where 
                                song_id=$songid");

                                if(mysql_affected_rows()>0) {
                                    $row=mysql_fetch_assoc($res);
                                    $kbps[$i]=trim($kbps[$i]);
                                    if(isset($kbps[$i]) && !empty($kbps[$i])) {
                                        $kb=$kbps[$i];
                                        $savedir=_ROOT_."/files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"]."/".$row["song_slug"];
                                        if(!file_exists($savedir)) {
                                            mkdir($savedir,0755,true);
                                        }
                                        $savedir="$savedir/$kb.mp3";
                                        if(file_put_contents($savedir,$data)) {
						$imgPath=_ROOT_."/images/".$song["album_slug"]."_300";
						if(!file_exists($imgPath)) {
							$imgPath=_ROOT_."/theme/qrcode.png";
						}
					    if(replaceIDTags($savedir, $imgPath,$song)) {
                                                //$size=round(($size/1024)/1024)." MB";
                                                $size=formatBytes(filesize($savedir));
                                                sql("insert into files(file_song_id,kbps,file_size) values($songid,'$kb','$size')");
                                                if(mysql_affected_rows()==1) {
                                                    $err["transloaded_files"][$i]="<font style='background:green'>OK</font>";
                                                }
                                            } else {
                                                $err["transloaded_files"][$i]="<font style='background:red'>ID3Tag Failed</font>";
                                            }
                                        } else {
                                            $err["transloaded_files"][$i]="<font style='background:red'>Upload Failed</font>";
                                        }
                                    } else {
                                        $err["transloaded_files"][$i]="<font style='background:red'>KBPS ?</font>";
                                    }
                                } else {
                                    $err["transloaded_files"][$i]="<font style='background:red'>Invalid Song</font>";
                                }
                            } else {
                                $err["transloaded_files"][$i]="<font style='background:red'>Invalid URL</font>";
                            }
                        }
                    }
                }
            }
    ?>
    <div class='content form1'>
            <fieldset>
                <form class='form1' method='post'>
                    <fieldset>
                        <table class='table1'>
                            <?php
                            if(isset($action) && $action=="save") {
                                $album=filterInput(INPUT_POST,"album");
                                $name=filterInput(INPUT_POST,"name",true,false,true);
                                $slug=filterInput(INPUT_POST,"slug");
                                if(isset($album) && isset($name) && isset($slug)) {
                                    echo "<tr><th colspan='7'>";
                                    if(preg_match("/[^0-9a-zA-Z-]/", $slug)!=0) {
                                        echo "<font style='background:red'>Invalid Slug</font>";
                                    } else {
                                        $res=sql("select * from songs where song_id!=$songid and lower(song_slug)='$slug'");
                                        if(mysql_affected_rows()>0) {
                                            echo "<font style='background:red'>Slug Already Exists</font>";
                                        } else {
                                            $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug,song_slug from categories sup 
                                            left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                                            left join albums on album_category_id=sub.category_id
                                            left join songs on song_album_id=album_id
                                            left join files on file_song_id=song_id 
                                            where 
                                            song_id=$songid");
                                            if(mysql_affected_rows()>0) {
                                                $row=mysql_fetch_assoc($res);
                                                $old=_ROOT_."/files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"]."/".$row["song_slug"];
                                                
                                                $res=sql("select sup.category_slug as sup,sub.category_slug as sub,album_slug,song_slug from categories sup 
                                                left join categories sub on sup.category_parent_id=0 and sub.category_parent_id=sup.category_id
                                                left join albums on album_category_id=sub.category_id
                                                left join songs on song_album_id=album_id
                                                left join files on file_song_id=song_id 
                                                where 
                                                album_id=$album");
                                                if(mysql_affected_rows()>0) {
                                                    $row=mysql_fetch_assoc($res);
                                                    $new=_ROOT_."/files/".$row["sup"]."/".$row["sub"]."/".$row["album_slug"]."/".$slug;
                                                    $flag=false;
                                                    if(file_exists($old)) {
                                                        $flag=rename($old,$new);
                                                    } else {
                                                        $flag=mkdir($old,0755,true);
                                                    }
                                                    if($flag) {
                                                        sql("update songs set song_album_id=$album, song_name='$name', song_slug='$slug' where song_id=$songid");
                                                        if(mysql_affected_rows()>=0) {
                                                            $res=sql("select * from songs,albums where song_id=$songid and album_id=song_album_id");
                                                            if(mysql_affected_rows()>0) {
                                                                $song=mysql_fetch_assoc($res);
                                                            }
                                                            echo "<font style='background:green'>Successfully saved</font>";
                                                        } else {
                                                            echo "<font style='background:red'>Error while updating</font>";
                                                        }
                                                    } else {
                                                        echo "<font style='background:red'>MOVE Failed</font>";
                                                    }
                                                } else {
                                                    echo "<font style='background:red'>Invalid Album</font>";
                                                }
                                            } else {
                                                echo "<font style='background:red'>Invalid Song</font>";
                                            }
                                        }
                                    }
                                    echo "</th></tr>";
                                }
                            }
                            ?>
                            <tr>
                                <th align='right'><b>Album:</b></th><td>
									<?php if(false) { ?>
									<select name='album'>
                                    <?php
                                    $res=sql("select * from albums");
                                    if(mysql_affected_rows()>0) {
                                        while($row=mysql_fetch_assoc($res)) {
                                            if($row["album_id"]==$song["album_id"]) {
                                                echo "<option value='".$row["album_id"]."' selected>".$row["album_name"]."</option>";
                                            } else {
                                                echo "<option value='".$row["album_id"]."'>".$row["album_name"]."</option>";
                                            }
                                        }
                                    }
                                    ?>
                                    </select>
									<?php } ?>
									<input type="text" id="inputAlbumSearch" placeholder="album search" value="<?=$song["album_name"]?>" />
									<input type="hidden" name="album" id="inputAlbum" value="<?=$song["album_id"]?>" />
									<div class="album-search-block"></div>
                                </td>
                                <th align='right'><b>Song: </b></th><td><input type='text' name='name' placeholder="song name" value='<?=$song["song_name"]?>' /></td>
                                <th align='right'><b>Slug: </b></th><td><input type='text' name='slug' placeholder="song slug" value='<?=$song["song_slug"]?>' /></td>
                                <th><input type='submit' name='action' value='SAVE' /></th>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </fieldset>
            <fieldset  style='margin:0px;padding:0px;width:auto' class='lefty'>
                <form class='form1' method="post" enctype="multipart/form-data">
                <table class='table1' style='padding:0px;margin:0px'>
                    <tr><th colspan="3">Upload MP3 File(s):</th></tr>
                    <tr><th>File</th><th>Kbps</th><th>Status</th></tr>
                    <?php
                    for($i=0;$i<5;$i++) {
                    ?>
                    <tr>
                        <td>
                            <input type='file' name="mp3file[]" multiple="false" />
                        </td>
                        <td>
                            <input type="text" name="kbps1[]" placeholder="kbps for uploading files" />
                        </td>
                        <td><?php
                            if(isset($err["uploaded_files"][$i])) {
                                echo $err["uploaded_files"][$i];
                            }
                            ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th colspan="3">
                            <input type="submit" name="action" value="Upload" />
                        </th>
                    </tr>
                </table>
                </form>
                <br/>
                <form class='form1' method="post" enctype="multipart/form-data">
                <table class='table1' style='padding:0px;margin:0px'>
                    <tr><th colspan="3">Transload MP3 File(s):</th></tr>
                    <tr><th>File</th><th>Kbps</th><th>Status</th></tr>
                    <?php
                    for($i=0;$i<5;$i++) {
                    ?>
                    <tr>
                        <td>
                            <input type='text' name="linkfile[]" placeholder='links' />
                        </td>
                        <td>
                            <input type="text" name="kbps2[]" placeholder="kbps for transloading files" />
                        </td>
                        <td><?php
                            if(isset($err["transloaded_files"][$i])) {
                                echo $err["transloaded_files"][$i];
                            }
                            ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th colspan="3">
                            <input type="submit" name="action" value="Transload" />
                        </th>
                    </tr>
                </table>
                </form>
            </fieldset>
            <fieldset class='righty'>
                <table class='table1' style='width:100%'>
                    <tr>
                        <th>KBPS</th><th>SIZE</th><th>ACTION</th>
                    </tr>
                    <?php
                    $res=sql("select * from files, songs where song_id=$songid and file_song_id=song_id");
                    if(mysql_affected_rows()>0) {
                        while($row=  mysql_fetch_assoc($res)) {
                            echo "<tr><td>".$row["kbps"]."</td><td>".$row["file_size"]."</td><td><input type='button' value='DELETE' fileid='".$row["file_id"]."' class='deleteSongFile' songid='$songid' /></td></tr>";
                        }
                    }
                    ?>
                </table>
            </fieldset>
        </form>
    </div>
    <?php
        } else {
            echo "<div class='content'>";
            echo "<h2>Sorry, selected song is not found on server.</h2><br/>";
            echo '<input type=button value="GO BACK" onClick="document.location=\'/admin/songs\'" style="padding:5px 10px" />';
            echo "</div>";
        }
    ?>
    
    <?php
    } else {
        $album=filterInput(INPUT_POST,"album");
        $name=filterInput(INPUT_POST,"name",true,false,true);
        $slug=filterInput(INPUT_POST,"slug");
        $action=filterInput(INPUT_POST,"action");
        if(isset($action)) {
            if(!isset($album) || (isset($album) && empty($album))) {
                $err["album"]="<font style='background:red'>Invalid Album</font>";
            } else if(!isset($name) || (isset($album) && empty($name))) {
                $err["name"]="<font style='background:red'>Invalid Name</font>";
            } else if(!isset($slug) || (isset($slug) && (empty($slug) || preg_match("/[^0-9a-zA-Z-]/", $slug)!=0))) {
                $err["slug"]="<font style='background:red'>Invalid Slug</font>";
            } else {
                $res=sql("select 1 from songs where lower(song_slug)='$slug'");
                if(mysql_affected_rows()>0) {
                    $err["slug"]="<font style='background:red'>Already exists</font>";
                } else {
                    $res=sql("insert into songs(song_name,song_slug,song_album_id) values('$name','$slug',$album)");
                    if(mysql_affected_rows()==1) {
                        $res=sql("select last_insert_id() as lastid");
                        $row=mysql_fetch_assoc($res);
                        $songid=$row["lastid"];
                        //header("Location: /admin/songs/new/?songid=$songid");
						echo '<script>document.location="/admin/songs/new/?songid='.$songid.'";</script>';
                    } else {
                        $err["gen"]="<font style='background:red'>Please try again, error while inserting song.</font>";
                    }
                }
            }
        }
    ?>
    <div class='content'>
        <form class="form1" method="post" id="formAddAlbum" enctype="multipart/form-data">
            <h2>New Song</h2>
            <span><?=isset($err["gen"])?$err["gen"]:""?></span><br/>
            <fieldset>
                <fieldset class='lefty'>
                    <label>Album:*</label> <?=isset($err["album"])?$err["album"]:""?>
					<?php
					if(false) {
					?>
                    <select name='album'>
                        <option style='display: none'>Select Album</option>
                        <?php
                        $res=sql("select * from albums");
                        if(mysql_affected_rows()>0) {
                            while($row=mysql_fetch_assoc($res)) {
                                echo "<option value='".$row["album_id"]."'>".$row["album_name"]."</option>";
                            }
                        }
                        ?>
                    </select>
					<?php
					}
					?>
					<input type="text" id="inputAlbumSearch" placeholder="album search" />
					<input type="hidden" name="album" id="inputAlbum" />
					<div class="album-search-block"></div>
                </fieldset>
                <fieldset class='lefty'>
                    <label>Song Name:</label> <?=isset($err["song"])?$err["song"]:""?>
                    <input type='text' name='name' placeholder='song name' />
                </fieldset>
                <fieldset class='lefty'>
                    <label>Song Slug:</label> <?=isset($err["slug"])?$err["slug"]:""?>
                    <input type='text' name='slug' placeholder='song slug' />
                </fieldset>
            </fieldset>
            <fieldset>
                <input type='submit' name='action' value='NEXT' class='lefty' />
                <input type='button' value='BACK' class='righty' onclick="document.location='/admin/songs'" />
            </fieldset>
        </form>
    </div>
    <?php
    }
    ?>
</div>