<br/>
<h2>Request For Content</h2>
<br/>
<form method="post">
	<?php
	$name=filterInput(INPUT_POST,"name");
	$email=filterInput(INPUT_POST,"email");
	$mobile=filterInput(INPUT_POST,"mobile");
	$rtype=filterInput(INPUT_POST,"content_type");
	$message=filterInput(INPUT_POST,"message");
	$action=filterInput(INPUT_POST,"action");
	if(!isset($action)) {
	} else if(!isset($name) || (isset($name) && empty($name))) {
		echo "<font color='red'>Please enter valid name</font>";
	} else if(!isset($email) || (isset($email) && (empty($email) || !isEmail($email)))) {
		echo "<font color='red'>Please enter valid e-mail address</font>";
	} else if(!isset($mobile) || (isset($mobile) && (empty($mobile) || !is_numeric($mobile)))) {
		echo "<font color='red'>Please enter valid mobile number</font>";
	} else if(!isset($rtype) || (isset($rtype) && (empty($rtype) || ($rtype!="album" && $rtype!="song")))) {
		echo "<font color='red'>Please select request type</font>";
	} else if(!isset($message) || (isset($message) && empty($message))) {
		echo "<font color='red'>Please enter valid request message</font>";
	} else {
		$res=sql("insert into requests(request_name,request_email,request_mobile,request_type,request_message) values('$name','$email','$mobile','$rtype','$message')");
		if(mysql_affected_rows()==1) {
			echo "<font color='green'>Request Submitted Successfully.</font>";
			$name="";
			$email="";
			$mobile="";
			$rtype="";
			$message="";
		} else {
			echo "<font color='red'>Error #1</font>";
		}
	}
	?>
	<br/><br/>
	<p><b>Name: *</b><br/><input type="text" class="form-control" name="name" value="<?=$name?>" style="padding:5px 10px" placeholder="enter your name" /></p>
	<br/>
	<p><b>E-Mail Address: *</b><br/><input type="text" value="<?=$email?>" name="email" style="padding:5px 10px" placeholder="enter your e-mail address" /></p>
	<br/>
	<p><b>Mobile Number:</b><br/><input type="text" name="mobile" value="<?=$mobile?>" style="padding:5px 10px" placeholder="enter your mobile number" /></p>
	<br/>
	<p><b>Request For:</b><br/><select name="content_type" style="padding:5px 10px" placeholder="select content type"><option disabled selected>Select Request Type</option><option <?=$rtype=="album"?"selected":""?> value="album">Album Request</option><option <?=$rtype=="song"?"selected":""?>  value="song">Song Request</option></select></p>
	<br/>
	<p><b>Request Message:</b><br/><textarea name="message" style="padding:5px 10px" placeholder="enter your request message" ><?=$message?></textarea></p>
	<br/>
	<input type="submit" value="Submit Request" name="action" style="padding:5px 10px;" />
</form>
<br/>
<br/>