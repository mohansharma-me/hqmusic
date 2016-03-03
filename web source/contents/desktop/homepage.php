<?php
$count=1;
$res=sql("select b.*,a.category_slug as parent_slug from (select * from categories where category_parent_id=0) a,(select * from categories where category_parent_id!=0) b where a.category_id=b.category_parent_id and b.category_parent_id!=0");
if(mysql_affected_rows()>0) {
while($row=mysql_fetch_assoc($res)) {
if($count==1) {
	echo '<div class="row">';
}
?>
<div class="col-md-3">
	<div class="box">
	<table class="table table-order">
		<thead>
			<tr>
				<th colspan=2><?=$row["category_name"]?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$res1=sql("select * from albums where album_category_id=".$row["category_id"]." order by album_art, album_year desc limit 8");
		if(mysql_affected_rows()>0) {
		while($row1=mysql_fetch_assoc($res1)) {
		?>
			<tr>
				<td width="2" class="table-order-img"><a style="" href="/<?=$row1["album_slug"]?>"><img src="/images/<?=$row1["album_slug"]?>_thumb.png"  style="height:50px;width:50px"/></a></td>
				<td style="word-wrap:break-word;text-align:center;line-height:2em">
					<a style="" class="fa" href="/<?=$row1["album_slug"]?>"> &nbsp;<?=$row1["album_name"]?></a>
				</td>
			</tr>
		<?php
		}
		}
		?>
		<tr>
			<td colspan=2 align=center><br/><a class="btn btn-primary" href="/<?=$row["parent_slug"]?>/<?=$row["category_slug"]?>">View More</a></td>
		</tr>
		</tbody>
	</table>
	</div>
</div>
<?php
if($count%2==0) {
	echo '</div><div class="gap"></div><div class="row">';
}
$count++;
}
if($count%2!=0 || $count==2) {
	echo "</div>";
}
}
?>
<div class="gap"></div>