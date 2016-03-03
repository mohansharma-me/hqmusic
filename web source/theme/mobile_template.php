<?php
if(isset($contentPage)) {
    if(is_file($contentPage) && file_exists($contentPage)) {
        include_once "./theme/mobile/upper.php";
        include_once "$contentPage";
        include_once "./theme/mobile/lower.php";
    } else {
        header("HTTP/1.1 404 Not Found");
    }
} else {
    header("HTTP/1.1 400 Invalid Request");
}