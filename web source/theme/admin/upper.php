<!doctype html>
<html>
    <head>
        <?php
        if(isset($myTitle)) {
        ?>
        <title><?=$myTitle?> - HQMusic.in</title>
        <?php
        } else {
        ?>
        <title>HQ Music</title>
        <?php
        }
        ?>
        <link rel="stylesheet" href="/theme/admin.css" media="screen" />
        <link rel="stylesheet" href="/theme/admin-new.css" media="screen" />
        <link rel="stylesheet" href="/theme/jquery-ui.css" media="screen" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        if(isset($myHead)) {
            echo $myHead;
        }
        ?>
		<script type="text/javascript" src="/theme/jquery.js"></script>
		<script>
		$(document).ready(function() {
			$(".page .sidebar").height($(document).height());
			$(".page .content-holder").height($(document).height());
		});
		</script>
    </head>
    <body>
        <?php
        if(isAuthed()) {
        ?>
        <div class='page'>
            <div class='sidebar'>
                <div class='logo'>
                    <a class="adlink" href='/admin/categories'>
                        <img src='/theme/logo.png' />
                    </a>
                </div>
                <div class='side-content'>
                    <div class='navigation'>
                        <ul class="adminLinks">
                            <li><a class="dlink1" href='/admin/categories'>Categories</a></li>
                            <li><a class="dlink1" href='/admin/sub-categories'>Sub-Categories</a></li>
                            <li><a class="dlink1" href='/admin/albums'>Albums</a></li>
                            <li><a class="dlink1" href='/admin/songs'>Songs</a></li>
                            <li><a class="dlink1" href='/admin/profile'>Profile</a></li>
                            <li><a href="/admin/?logout=yes">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class='content-holder'>
        <?php
        }
        ?>    