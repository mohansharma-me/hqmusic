<?php
if(isset($contentPage)) {
    if(is_file($contentPage) && file_exists($contentPage)) {
        include_once "./theme/admin/upper.php";
        include_once "$contentPage";
        include_once "./theme/admin/lower.php";
    } else {
        header("HTTP/1.1 404 Not Found");
    }
} else {
    header("HTTP/1.1 400 Invalid Request");
}