<?php
function isAuthed() {
    if(isset($_SESSION["adminAuth"]) && isset($_SESSION["adminData"]) && is_array($_SESSION["adminData"])) {
        $current=$_SESSION["adminAuth"];
        return strcmp($current,  getIPHash($_SESSION["adminData"]["username"]))==0;
    } else {
        return false;
    }
}

function getAuthenticated() {
    $flag=false;
    $username=filterInput(INPUT_POST,"username");
    $password=filterInput(INPUT_POST,"password");
    if(isset($username) && isset($password)) {
        $res=sql("select * from authentication");
        if(is_resource($res)) {
            while($row=mysql_fetch_assoc($res)) {
                if(strcmp($row["username"],$username)==0 && strcmp($row["password"],hash_hmac("sha1",$password,"webcodez"))==0) {
                    $_SESSION["adminData"]=$row;
                    $_SESSION["adminAuth"]=getIPHash($row["username"]);
                    $flag=true;
                    break;
                }
            }
        }
    }
    return $flag;
}

function dropAuthentication() {
    session_unset();
    session_destroy();
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}


function recursive_array_replace($find, $replace, $array){
    if (!is_array($array)) {
        $arr=explode(" ",trim($array));
        $output="";
        foreach($arr as $ar) {
            if($output==" ") {
                $output=preg_replace($find,$replace,$ar);
            } else {
                $output.=" ".preg_replace($find,$replace,$ar);
            }
        }
        return $output;
        //return preg_replace($find,$replace,$array);
    } else {
		//echo "<pre>";
		//print_r($array);
		//echo "</pre>";
	}
    $newArray = array();
    foreach ($array as $key => $value) {
            $newArray[$key] = recursive_array_replace($find, $replace, $value);
    }
    return $newArray;
}

function replaceIDTags($file,$poster,$sa,$domain="HQMusic.in") {
    $tagging_format = "UTF-8";
    $path = $file;
    require_once('./getid3/getid3.php');
    require_once('./getid3/write.php');
	
	$tagdata=array(); //////
	
    $getID3 = new getID3;
    $getID3->setOption(array('encoding'=>$tagging_format));
    $tagdata = $getID3->analyze($path);
    if(isset($tagdata["tags"]["id3v2"])) {
        $tagdata = $tagdata['tags']['id3v2'];		
    } else {
        $tagdata["title"]=array($sa["song_name"]);
        $tagdata["artists"]=array($sa["album_cast"]);
        $tagdata["album-artists"]=array($sa["album_cast"]);
        $tagdata["album"]=array($sa["album_name"]." - $domain");
        $tagdata["year"]=array($sa["album_year"]);
		$tagdata["composer"]=$tagdata["performer"]=$tagdata["lyricts"]=$tagdata["conductors"]=$tagdata["publisher"]=$tagdata["url"]=array("$domain");
		
        try {
            $json=json_decode($sa["album_imdb_json"],true);
            $tagdata["genre"]=array($json["genre"]);
            $tagdata["director"]=array($json["director"]);
            $tagdata["actors"]=array($json["actors"]);
        } catch (Exception $ex) {

        }
    }
	
	$tagdata["title"]=array($sa["song_name"]);
	$tagdata["artists"]=array($sa["album_cast"]);
	$tagdata["album-artists"]=array($sa["album_cast"]);
	$tagdata["album"]=array($sa["album_name"]." - $domain");
	$tagdata["year"]=array($sa["album_year"]);
	
	$tagdata["composer"]=$tagdata["performer"]=$tagdata["lyricts"]=$tagdata["conductors"]=$tagdata["publisher"]=$tagdata["url"]=array("$domain");
	try {
		$json=json_decode($sa["album_imdb_json"],true);
		$tagdata["genre"]=array($json["genre"]);
		$tagdata["director"]=array($json["director"]);
		$tagdata["actors"]=array($json["actors"]);
	} catch (Exception $ex) {

	}
    $patern="/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/";
    $tagdata = recursive_array_replace($patern,$domain,$tagdata);
    $tagwriter = new getid3_writetags;
    $tagwriter->filename = $path;
    $tagwriter->tagformats = array('id3v1', 'id3v2.3');
    $tagwriter->overwrite_tags = true;
    $tagwriter->tag_encoding = $tagging_format;
    $tagwriter->remove_other_tags = true;

    //read the APIC
    ob_start();
    if($fd = fopen($poster,'rb'))
    {
        ob_end_clean();
        $image_data = fread($fd,filesize($poster));
        fclose($fd);
    }

    $tagdata['attached_picture'][0]['data'] = $image_data;
    $tagdata['attached_picture'][0]['picturetypeid'] = "3";
    $tagdata['attached_picture'][0]['description'] = $domain;
    $tagdata['attached_picture'][0]['mime'] = "image/jpeg";

	
    $tagwriter->tag_data = $tagdata;

	//$tagdata["comment"]=array("ASDASDASD");
	
	
	
	$oflag=$tagwriter->WriteTags();
		/*echo "<pre>AFTER ERROR:<br/><br/>";
		print_r($tagdata);
		echo "<br/><br/>";
		print_r($tagwriter->errors);	
		echo "</pre>";*/
    if($oflag)
    {
//		echo "YES";
        return true;
    }
    else
    {
//		echo "NO";
        return false;
    }
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 