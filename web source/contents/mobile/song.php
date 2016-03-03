<div class="content-holder">
    <div class="content">
        <ul class="style-list1">
            <h1><b>Download</b><br/><br/><?=$myData["song_name"]?> - <?=$myData["album_name"]?></h1>
            <?php
            $res=sql("select * from songs s, files f where s.song_id='".$myData["song_id"]."' and f.file_song_id=s.song_id");
            if(is_resource($res)) {
                echo "<li style='text-align:center'><label><span>Click on <b>`Kbps`</b> to download song</span></label><br/><br/>";
                while($row=mysql_fetch_assoc($res)) {
                    /*$link=strtolower(trim($myData["album_slug"]));
                    $link.="/".$myData["song_slug"]."/?kbps=".trim($row["kbps"]);
                    $link.="&sign=".getIPHash($link);*/
                    $link=strtolower(trim($myData["album_slug"]));
                    $link.="/".$myData["song_slug"]."/".trim($row["kbps"])."/";
					$fakeFile="[HQMusic.in] ".$myData["song_name"]." - ".$myData["album_name"]." ".$row["kbps"]."".""."Kbps";
                    $link.=getIPHash($link)."/$fakeFile.mp3";
                    echo "[ <a href='/$link'>".$row["kbps"]." Kbps</a> ]&nbsp;";
                }
                echo "<br/><br/></li>";
            } else if(is_numeric($res) && $res==0) {
                echo "<li><label><font color='green'>No Songs.</font></label></li>";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'>Can't connect to database.</font></label></li>";
            }
            ?>
        </ul>
		
		<ul class="style-list1">
			<h1><b>Related Songs</b></h1>
			<?php
            $res=sql("select * from songs s where s.song_id!=".$myData["song_id"]." and song_album_id=".$myData["album_id"]);
            if(is_resource($res)) {
                while($row=mysql_fetch_assoc($res)) {
                    /*$link=strtolower(trim($myData["album_slug"]));
                    $link.="/".$myData["song_slug"]."/?kbps=".trim($row["kbps"]);
                    $link.="&sign=".getIPHash($link);*/
                    //$link=strtolower(trim($myData["album_slug"]));
                    $link="".$myData["album_slug"]."/".trim($row["song_slug"])."";
                    //$link.=getIPHash($link).".mp3";
                    echo "<li><a href='/$link'>".$row["song_name"]."</a></li>";
                }
            } else if(is_numeric($res) && $res==0) {
                echo "<li><label><font color='green'>No Songs.</font></label></li>";
            } else if(is_numeric($res) && $res==-1) {
                echo "<li><label><font color='red'>Can't connect to database.</font></label></li>";
            }
            ?>
		</ul>
        
        <ul class="style-list1 center-list">
            <li>
            <a href="/">Home</a> &gt;&gt;
            <?php
                $parent_cat_slug="";
                $res=sql("select s.song_name,s.song_slug,a.album_name,a.album_slug,sup.category_slug as sup,sup.category_name as sup_name,sub.category_slug as sub,sub.category_name as sub_name,a.album_slug as album,s.song_slug as song from categories sup, categories sub, albums a, songs s where lower(s.song_slug)='".strtolower(trim($myData["song_slug"]))."' and a.album_id=s.song_album_id and sub.category_id=a.album_category_id and sup.category_id=sub.category_parent_id");
                if(is_resource($res)) {
                    $row=mysql_fetch_assoc($res);
                    $sup=$row["sup"];
                    $sup_name=$row["sup_name"];
                    $sub=$row["sub"];
                    $sub_name=$row["sub_name"];
                    $album=$row["album_slug"];
                    $album_name=$row["album_name"];
                    $song=$row["song_slug"];
                    $song_name=$row["song_name"];
                    echo "<a href='/$sup'>$sup_name</a> &gt;&gt; <a href='/$sup/$sub'>$sub_name</a> &gt;&gt; <a href='/$album'>$album_name</a> &gt;&gt; <a href='/$album/$song'>$song_name</a>";
                }
            ?>
            </li>
        </ul>
    </div>
</div>