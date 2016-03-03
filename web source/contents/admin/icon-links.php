<?php
$keys=$myData["keys"];
if(FALSE) {
?>
<div class="icon-links">
    <li class="<?=(isset($keys[1]) && strtolower(trim($keys[1]))=="categories")?"current":""?>">
        <a href="/admin/categories"><img src="/theme/images/users.png" /><br/><span>Categories</span></a>
    </li>
    <li class="<?=(isset($keys[1]) && strtolower(trim($keys[1]))=="sub-categories")?"current":""?>">
        <a href="/admin/sub-categories"><img src="/theme/images/users.png" /><br/><span>Sub-Categories</span></a>
    </li>
    <li class="<?=(isset($keys[1]) && strtolower(trim($keys[1]))=="albums")?"current":""?>">
        <a href="/admin/albums"><img src="/theme/images/users.png" /><br/><span>Albums</span></a>
    </li>
    <li class="<?=(isset($keys[1]) && strtolower(trim($keys[1]))=="songs")?"current":""?>">
        <a href="/admin/songs"><img src="/theme/images/users.png" /><br/><span>Songs</span></a>
    </li>

    <li class="righty <?=(isset($keys[1]) && strtolower(trim($keys[1]))=="logout")?"current":""?>">
        <a href="/admin/?logout=true"><img src="/theme/images/users.png" /><br/><span>Logout</span></a>
    </li>            
    <li class="righty <?=(isset($keys[1]) && strtolower(trim($keys[1]))=="profile")?"current":""?>">
        <a href="/admin/profile"><img src="/theme/images/users.png" /><br/><span><?=$_SESSION["adminData"]["name"]?></span></a>
    </li>
</div>
<?php
} else {
?>

<div class="text-head">
    <div class="text-head">
        <a href="/admin">
            <img src="/theme/images/users.png" />
            <?php
            if(isset($keys[1])) {
            ?>
            <h1><?=ucwords($keys[1])?></h1>
            <?php } else {?>
            <h1><?=ucwords($_SESSION["adminData"]["name"])?></h1>
            <?php }?>
        </a>
    </div>
</div>

<?php } ?>
