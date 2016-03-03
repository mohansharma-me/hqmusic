<?php
function slug($text) {
	 // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

  // trim
  $text = trim($text, '-');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text))
  {
    return 'n-a';
  }
  
  return $text;
}

function getIPHash($data=null) {
	//$key="mail@wcodez.com";
	$key=session_id();
    if($data==null) {
        return hash_hmac("sha1", gethostbyname(""), $key);
    } else {
        return hash_hmac("sha1", $data."".gethostbyname(""), $key);
    }
}


function isMobile() {
    if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) { // if mobile friendly
        return true;
    }
    
    if(isset($_SERVER["HTTP_ACCEPT"]) && preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) { // if support wap
        return true;
    }

    if(isset($_SERVER["HTTP_USER_AGENT"])){ // any of following mobile devices
        $user_agents = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
        foreach($user_agents as $user_string){
            if(preg_match("/".$user_string."/i",$_SERVER["HTTP_USER_AGENT"])) {
                return true;
            }
        }
    }
    
    if(isset($_SERVER["HTTP_USER_AGENT"])) {
        if(preg_match("/iphone/i",$_SERVER["HTTP_USER_AGENT"])) { // detect iphone
            return true;
        }
    }

    // if not mobile
    return false;
}

function isEmail($mail) {
    $flag=false;
    $arr=explode("@",$mail);
    if(count($arr)==2) {
        if(strlen(trim($arr[0]))>0 && strlen(trim($arr[1]))>0) {
            $flag=true;
        }
    }
    return $flag;
}
function filterInput($type,$name,$sqlParam=true,$lowerit=true,$trim=true) {
    $data=filter_input($type,$name);
    if(isset($data)) {
        if($lowerit) {
            $data=strtolower($data);
        } 
        if($trim) {
            $data=trim($data);
        }
        if($sqlParam) {
            return addslashes($data);
        } else {
            return $data;
        }
    } else {
        return $data;
    }
}

function giveDownload($searchFor,$newFile, $file, $contenttype = 'audio/mp3', $cacheSize=10,$transferCachePerSecond=1) {
    //@error_reporting(0);
    $cacheSize=$cacheSize*1024;
    if (!file_exists($file)) {
        //header("HTTP/1.1 404 Not Found");
        //echo "404 Not Found<br/>The requested URL `<a href='".$_SERVER["REQUEST_URI"]."'>".$_SERVER["REQUEST_URI"]."</a>` was not found in this server.<br/>Please contact administrator if error appears again and again.<br/><a href='/'>HQMusic.in</a>";
	header("Location: /search/?q=$searchFor");
        exit;
    }
    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = $_SERVER['HTTP_RANGE'];
    } else if ($apache = apache_request_headers()) {
        $headers = array();
        foreach ($apache as $header => $val) $headers[strtolower($header)] = $val;
        if (isset($headers['range'])) {
            $range = $headers['range'];
        } else {
          $range = FALSE;
        }
    } else {
        $range = FALSE; // We can't get the header/there isn't one set
    }

    // Get the data range requested (if any)
    $filesize = filesize($file);
    if ($range) {
        $partial = true;
        list($param,$range) = explode('=',$range);
        if (strtolower(trim($param)) != 'bytes') { // Bad request - range unit is not 'bytes'
          //header("HTTP/1.1 400 Invalid Request");
          //echo "400 Invalid Request<br/>The requested URL `<a href='".$_SERVER["REQUEST_URI"]."'>".$_SERVER["REQUEST_URI"]."</a>` was not found in this server.<br/>Please contact administrator if error appears again and again.<br/>";
	  header("Location: /search/?q=$searchFor");
          exit;
        }
        $range = explode(',',$range);
        $range = explode('-',$range[0]); // We only deal with the first requested range
        if (count($range) != 2) { // Bad request - 'bytes' parameter is not valid
          //header("HTTP/1.1 400 Invalid Request");
          header("Location: /search/?q=$searchFor");
	  exit;
	  
        }
        if ($range[0] === '') { 
          $end = $filesize - 1;
          $start = $end - intval($range[0]);
        } else if ($range[1] === '') { 
          $start = intval($range[0]);
          $end = $filesize - 1;
        } else { 
          $start = intval($range[0]);
          $end = intval($range[1]);
          if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) {
              $partial = false; // Invalid range/whole file specified, return whole file
          }
        }
        $length = $end - $start + 1;
    } else {
        $partial = false; // No range requested
        $length=$filesize;
    }

    // Send standard headers
    header("Content-Type: $contenttype");
    header("Content-Length: $filesize");
    header('Content-Disposition: attachment; filename="'.$newFile.'"');
    header('Accept-Ranges: bytes');

    // if requested, send extra headers and part of file...
    if ($partial) {
        header('HTTP/1.1 206 Partial Content'); 
        header("Content-Range: bytes $start-$end/$filesize"); 
        if (!$fp = fopen($file, 'r')) { // Error out if we can't read the file
          //header("HTTP/1.1 500 Internal Server Error");
          //echo "500 Internal Server Error<br/>The requested URL `<a href='".$_SERVER["REQUEST_URI"]."'>".$_SERVER["REQUEST_URI"]."</a>` was not found in this server.<br/>Please contact administrator if error appears again and again.<br/>";
	  header("Location: /search/?q=$searchFor");
          exit;
        }
        if ($start) fseek($fp,$start);
        while ($length) { // Read in blocks of 8KB so we don't chew up memory on the server
          $read = ($length > $cacheSize) ? $cacheSize : $length;
          $length -= $read;
          print(fread($fp,$read));
          flush();
          sleep($transferCachePerSecond);
        }
        fclose($fp);
    } else {
        //readfile($file);
        if (!$fp = fopen($file, 'r')) { // Error out if we can't read the file
          //header("HTTP/1.1 500 Internal Server Error");
          //echo "500 Internal Server Error<br/>The requested URL `<a href='".$_SERVER["REQUEST_URI"]."'>".$_SERVER["REQUEST_URI"]."</a>` was not found in this server.<br/>Please contact administrator if error appears again and again.<br/>";
	  header("Location: /search/?q=$searchFor");
          exit;
        }
        while ($length) { // Read in blocks of 8KB so we don't chew up memory on the server
          $read = ($length > $cacheSize) ? $cacheSize : $length;
          $length -= $read;
          print(fread($fp,$read));
          flush();
          sleep($transferCachePerSecond);
        }
        fclose($fp);
    }
    exit;
}

function loadMobileContent($myTitle,$myHead,$contentPage,$myData=array()) {
    include_once "./theme/mobile_template.php";
}

function loadAdminContent($myTitle,$myHead,$contentPage,$myData=array()) {
    include_once "./theme/admin_template.php";
}

function sql($query) {
    if($_SERVER["HTTP_HOST"]=="localhost:90")  {
        $connection=mysql_connect("localhost","root","");
        $database=mysql_select_db("hqmusic", $connection);
    } else {
        $connection=mysql_connect("localhost","root","b@maboy");
        $database=mysql_select_db("hqmusic", $connection);
    }
    $result=mysql_query($query);
    if(mysql_affected_rows()>0) {
        return $result;
    } else if(mysql_affected_rows()==0) {
        return 0;
    } else {
        return -1;
    }
}
